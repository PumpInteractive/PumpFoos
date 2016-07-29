function str_pad_left(string, pad, length) {
      return (new Array(length+1).join(pad)+string).slice(-length);
}

function Clock()
{
      this.on = false;
      this.real_start_time = 0;
      this.current_time = 0;
}

Clock.prototype.start = function() {
      var self = this;

      this.on = true;
      this.real_start_time = Math.round(new Date().getTime() / 1000);
      setTimeout(function(){
            self.tick(self);
      }, 500);
};

Clock.prototype.stop = function() {
      this.on = false;
};

Clock.prototype.tick = function(self) {

      this.current_time = Math.round(new Date().getTime() / 1000) - this.real_start_time;

      var minutes = Math.floor(this.current_time / 60);
      var seconds = this.current_time - (minutes * 60);

      $('#game_clock').text(str_pad_left(minutes,'0',2)+':'+str_pad_left(seconds,'0',2));

      if(this.on) {
            setTimeout(function(){
                  self.tick(self);
            }, 500);
      }
};
