
URL = window.URL || window.webkitURL;

var gumStream; 
var rec; 
var input; 
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext;

var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");

recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);

function startRecording() {
    console.log("recordButton clicked");
    var constraints = { audio: true, video: false };
    recordButton.disabled = true;
    navigator.mediaDevices
        .getUserMedia(constraints)
        .then(function (stream) {
            console.log(
                "getUserMedia() success, stream created, initializing Recorder.js ..."
            );
            audioContext = new AudioContext();
            gumStream = stream;
            input = audioContext.createMediaStreamSource(stream);
            rec = new Recorder(input, { numChannels: 1 });
            rec.record();
            console.log("Recording started");
        })
        .catch(function (err) {
            recordButton.disabled = false;
        });
}



function stopRecording() {
    console.log("stopButton clicked");
    recordButton.disabled = false;
    rec.stop();
    gumStream.getAudioTracks()[0].stop();
    rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {
    var url = URL.createObjectURL(blob);
    var au = document.createElement("audio");
    var li = document.createElement("li");
    var link = document.createElement("a");
    var filename = new Date().toISOString();
    au.controls = true;
    au.src = url;
    li.appendChild(au);
    recordingsList.appendChild(li);
}
