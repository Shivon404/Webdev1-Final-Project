function playPauseToggle() {
  let video = document.getElementById("video");
  let playPauseIcon = document.getElementById("playPauseIcon");

  if (video.paused) {
    video.play();
    playPauseIcon.classList.remove("fa-play");
    playPauseIcon.classList.add("fa-pause");
  } else {
    video.pause();
    playPauseIcon.classList.remove("fa-pause");
    playPauseIcon.classList.add("fa-play");
  }
}

var video = document.getElementById("video");
var progressBar = document.getElementById("progressBar");

video.addEventListener("timeupdate", function() {
  var currentTime = video.currentTime;
  var duration = video.duration;
  progressBar.value = (currentTime / duration) * 100;
});
