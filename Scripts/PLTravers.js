/*
 *      PLTravers
 *      
 *      Code required to record a message
 */
 
var recorder;
var audio_context;
var current_url;
var recorder;
var user_media;
var recorder;

/*
 *      Let's make it available
 */

function createDownloadLink() 
{
    
    if (recorder)
    {
        recorder.exportWAV(function(blob) 
        {
            var url = URL.createObjectURL(blob);
            var li = document.createElement('li');
            var br = document.createElement('br');
            var au = document.createElement('audio');
            var hf = document.createElement('a');

            au.controls = true;
            au.src = url;
            hf.href = url;
            hf.download = new Date().toISOString() + '.wav';
            hf.innerHTML = hf.download;
            li.appendChild(au);
            li.appendChild(au);
            li.appendChild(br);
            li.appendChild(hf);
            recordingslist.appendChild(li);
            
        });
        
        //  Clear the contents of the recorder buffer
        recorder.clear();
    }    

}

/*
 *      Stop Recording
 */

function stopRecording(button) 
{
    if (recorder)
    {
        recorder.stop();
    }
    
    button.disabled = true;
    button.previousElementSibling.disabled = false;

    // create WAV download link using audio data blob
    createDownloadLink();

}

/*
 *      Start Recording
 */
        
function startRecording(button) 
{
    
    if (recorder)
    {
        recorder.record();        
    }

    button.disabled = true;
    button.nextElementSibling.disabled = false;

};


$( document ).ready(function() 
{
    
    //  Let's get it started
    audio_context = new AudioContext();    
    user_media = window.navigator.getUserMedia || window.navigator.webkitGetUserMedia;        
    current_url = window.URL || window.webkitURL;

    //  This requires a SSL layer to work effectively in chrome
    window.navigator.mediaDevices.getUserMedia({ audio: true })
        .then(function(stream) {
            
            //  Time to make a stream, hooray
            window.source = audio_context.createMediaStreamSource(stream);
        
            //  Instanciate the mic recorder with the stream
            recorder = new Recorder(window.source);
            
        });
        
});

