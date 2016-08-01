function Rotator(game) {
  var self = this;

  if (sessionStorage.getItem('rotate')) {
    this.presentPlayers = JSON.parse(sessionStorage.getItem('presentPlayers'));
    this.mode = 'rotate';
  } else {
    this.presentPlayers = [];
    this.mode = 'shuffle';
  }
}


Rotator.prototype.check_present_players = function check_present_players(context) {
  if (context === "beforeGame") {
    if ($('.present-players-inner .player').length >= $('#game_type_id option:selected').data('number_of_players')) {
      $('.present-players-set-button').slideDown(500);
    } else {
      $('.present-players-set-button').slideUp(500);
    }
  } else if (context === "startGame") {
    return $('.present-players-inner .player').length >= $('#game_type_id option:selected').data('number_of_players');
  }
};

Rotator.prototype.start = function start() {
  var self = this;
  $('#playersPresent .player').each(function() {
    self.presentPlayers.push($(this));
  });

  switch (self.mode) {
    case 'rotate':
      break;
    case 'shuffle':
      presentPlayers = self.shuffle(self.presentPlayers);
      break;
  }
  self.fill(presentPlayers);
};
Rotator.prototype.fill = function fill(presentPlayers) {
  var self = this;
  for (var i = 0; i < 4; i++ ) {
    player = $('[data-active-tray-id="' + (i + 1) + '"]').addClass('active').append(presentPlayers[i]);
    //Add the player to the game
    var new_player = bench[player.find('.player').data('player-id')];
    new_player.team = player.data('team');
    new_player.position = player.data('position');
    new_player.tray_id = player.data('active-tray-id');

    game.add_player(new_player);
  }
}
// Adapted from: How to randomize shuffle a javascript array -- http://stackoverflow.com/a/2450976
Rotator.prototype.shuffle = function shuffle(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;
    // While there remain elements to shuffle...
    while (0 !== currentIndex) {
        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;
        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }
    return array;
}
