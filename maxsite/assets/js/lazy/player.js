function ParseForPlayers(e) {
  var l = e.querySelectorAll('audio, iframe, video');
  var r = [];
  for (var c = 0; c < l.length; c++) {
    if (l[c].localName != 'iframe') {
      r.push(l[c]);
    } else {
      console.log(l[c]);
//      r.push(ParseForPlayers(l[c]));
    }
  }
  return r;
}

//var MyPlayers = ParseForPlayers(document);
MyPlayers = document.querySelectorAll('audio, video');
//MyPlayers.push(document.getElementsByTagName('video'));
//console.log(MyPlayers);

function OnPlayerStart(e) {
// console.log(e);
  for (var c = 0; c < MyPlayers.length; c++) {
    if (c != this.MyID) {
      MyPlayers[c].pause();
    }
  }
}

function OnTrackEnded(e) {
  if (this.MyID < MyPlayers.length) {
    MyPlayers[this.MyID + 1].play();
  }
}

// MyPlayers = document.getElementsByTagName('audio');
for (var c = 0; c < MyPlayers.length; c++) {
  MyPlayers[c].MyID = c;
  MyPlayers[c].addEventListener('playing', OnPlayerStart);
  MyPlayers[c].addEventListener('ended', OnTrackEnded);
}