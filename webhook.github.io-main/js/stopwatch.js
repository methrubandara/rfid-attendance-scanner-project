class Stopwatch
{
    constructor(displayElement)
    {
        this.displayElement = displayElement;
        this.remainingTime = 6 * 60; // 6 minutes in seconds
        this.interval = null;
    }

    start()
    {
        this.interval = setInterval(() =>
        {
            this.remainingTime--;
            this.updateDisplay();

            if (this.remainingTime <= 0)
            {
                this.stop();
            }
        }, 1000);
    }

    stop()
    {
        clearInterval(this.interval);
        this.displayElement.innerText = "Time's Up!";
    }

    updateDisplay()
    {
        const minutes = Math.floor(this.remainingTime / 60);
        const seconds = this.remainingTime % 60;
        this.displayElement.innerText = `${this.pad(minutes)}:${this.pad(seconds)}`;
    }

    pad(number)
    {
        return number < 10 ? '0' + number : number;
    }
}

