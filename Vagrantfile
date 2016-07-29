# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'yaml' # Load YAML parsing ruby library to parse config file

# Include the YAML project configuration - http://stackoverflow.com/questions/16708917/how-do-i-include-variables-in-my-vagrantfile
current_dir = File.dirname(File.expand_path(__FILE__))
project_config = YAML.load_file("#{current_dir}/Vagrantconfig.yaml")

# Store the Initialization and Startup provision scripts into global variables which are then loaded later in the Vagrant.configure method
# We generate them here so we can use HEREDOC
$initialize = <<INITIALIZE
  #!/bin/bash

  # Setup MySQL Logging to project log directory for help debugging
  printf "\nlog-slow-queries=/var/www/#{project_config['name']}/logs/slow_queries_log" >> /etc/my.cnf

  # Generate Self Signed SSL for <project_name>.dev if it doesn't exist in the project yet
  if [ ! -f /var/www/#{project_config['name']}/config/ssl/www.dev.crt ]; then
      openssl req \
          -new \
          -newkey rsa:4096 \
          -days 365 \
          -nodes \
          -x509 \
          -subj "/C=CA/ST=Denial/L=Calgary/O=Pump Interactive Development/CN=#{project_config['name']}.dev" \
          -keyout /var/www/#{project_config['name']}/config/ssl/www.dev.key \
          -out /var/www/#{project_config['name']}/config/ssl/www.dev.crt
  fi

  # Generate Self Signed SSL wildcard for *.<project_name>.dev if it doesn't exist in the project yet
  if [ ! -f /var/www/#{project_config['name']}/config/ssl/STAR.dev.crt ]; then
      openssl req \
          -new \
          -newkey rsa:4096 \
          -days 365 \
          -nodes \
          -x509 \
          -subj "/C=CA/ST=Denial/L=Calgary/O=Pump Interactive Development/CN=*.#{project_config['name']}.dev" \
          -keyout /var/www/#{project_config['name']}/config/ssl/STAR.dev.key \
          -out /var/www/#{project_config['name']}/config/ssl/STAR.dev.crt
  fi

  # Add SSL certificates to the file system through symlinks
  ln -s /var/www/#{project_config['name']}/config/ssl/www.dev.crt /etc/pki/ca-trust/source/www.dev.crt
  ln -s /var/www/#{project_config['name']}/config/ssl/STAR.dev.crt /etc/pki/ca-trust/source/STAR.dev.crt
  update-ca-trust force-enable
  update-ca-trust extract

  # Run Composer Install
  cd /var/www/#{project_config['name']}/data/www/ && composer install
INITIALIZE

# Symlink in all dev.conf configuration files into the $initialize script
for i in project_config['domains']
  $initialize << "ln -s /var/www/"+ project_config['name'] +"/config/"+ i +".dev.conf /etc/httpd/vhosts.d/"+ i +"."+ project_config['name'] +".dev.conf\n"
end

# Import project databases
unless project_config['databases'].empty?
  for i in project_config['databases']
      $initialize << "mysql -u root -proot < /var/www/"+ project_config['name'] +"/sql/"+ i +"\n"
      $initialize << "php /var/www/#{project_config['name']}/sql/apply-patches.php"
  end
end


$startup = <<STARTUP
  #!/bin/bash

  # Apache needs to be started with this provision script to allow vagrant to sync our folder first which contains the apache configuration file for our project
  service httpd start

STARTUP

Vagrant.configure(2) do |config|

  config.vm.box = "pumpinteractive/lamp"

  # set hostname for dev environment
  config.vm.hostname = project_config['name'] +".dev"

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network "private_network", type: "dhcp"

  # Disable the default synced folder, it's not necessary, see next line
  config.vm.synced_folder ".", "/vagrant", disabled: true

  # sync our working directory to the main website folder /var/www, WITHOUT NFS
  config.vm.synced_folder "./", "/var/www/"+ project_config['name']

  config.vm.provider "parallels" do |prl|
    prl.name = project_config['name']
    prl.customize ["set", :id, "--autostart", "auto"]
  end

  config.vm.provider "virtualbox" do |v, override|
    v.name = project_config['name']
    v.gui = false # Disable the GUI

    # Saves time setting up by using linked clone versions of boxes, rather than full copies
    v.linked_clone = true if Vagrant::VERSION =~ /^1.8/

    v.customize ["modifyvm", :id, "--autostart-enabled", "on"]

    #Sync time more often (times gets out of sync if host goes to sleep)
    v.customize [ "guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 10000 ]

    # sync our working directory to the main website folder /var/www, WITH NFS
    override.vm.synced_folder "./", "/var/www/"+ project_config['name'], nfs: true
  end

  # Enable Hostmanager to run on vagrant up and vagrant destroy
  config.hostmanager.enabled = true

  # Update the HOST (Your) machine /etc/hosts file
  config.hostmanager.manage_host = true

  # Update ths GUEST (vagrant) machine /etc/hosts file
  config.hostmanager.manage_guest = true

  # We Want the GUEST machine private IP address
  config.hostmanager.ignore_private_ip = false

  config.hostmanager.include_offline = false

  # Make hostmanager work with DHCP addresses, from https://github.com/devopsgroup-io/vagrant-hostmanager/issues/86
  cached_addresses = {}
  config.hostmanager.ip_resolver = proc do |vm, resolving_vm|
    if cached_addresses[vm.name].nil?
      if hostname = (vm.ssh_info && vm.ssh_info[:host])
        vm.communicate.execute("hostname -I | cut -d ' ' -f 2") do |type, contents|
          cached_addresses[vm.name] = contents.split("\n").first[/(\d+\.\d+\.\d+\.\d+)/, 1]
        end
      end
    end
    cached_addresses[vm.name]
  end

  # Have hostmanager add all of our aliases
  unless project_config['aliases'].empty?
    config.hostmanager.aliases = project_config['aliases']
  end

  # Initial Server provision script (Only runs on first vagrant up) to install Apache, PHP, MySQL, OpenSSL
  config.vm.provision "shell", inline: $initialize

  # Provision script which runs every time the machine starts
  config.vm.provision "shell", run: "always", inline: $startup

end
