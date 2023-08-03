const AUDIO_DIR = './assets/audio/';

export default class AudioFile {
    constructor(file, trigger) {
        this.file = new Audio(AUDIO_DIR + file);
        this.trigger = trigger;

        this.initListeners();
    }
    initListeners() {
        this.trigger.addEventListener("click", (e) => {
            e.preventDefault();
            this.trigger.classList.add("animate__animated");
            this.trigger.classList.add("animate__rubberBand");
            this.file.play();
        })
    }
    play() {
        this.file.play();
    }
}