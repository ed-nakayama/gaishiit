
document.addEventListener('DOMContentLoaded', function () {
    var questionHeaders = document.querySelectorAll('.question-header');

    function toggleArrowDisplay(header, showUp) {
        var arrowDown = header.querySelector('#ad-lp-arrow-down');
        var arrowUp = header.querySelector('#ad-lp-arrow-up');
        if (showUp) {
            arrowDown.style.display = 'none';
            arrowUp.style.display = 'inline';
        } else {
            arrowDown.style.display = 'inline';
            arrowUp.style.display = 'none';
        }
    }

    questionHeaders.forEach(function (header) {
        header.addEventListener('click', function () {
            var content = this.nextElementSibling;
            var isContentVisible = content.style.display === 'block';

           
            content.style.display = isContentVisible ? 'none' : 'block';
            toggleArrowDisplay(this, !isContentVisible);

            if (!isContentVisible) {
                this.classList.add('hover-style'); 
            } else {
                this.classList.remove('hover-style'); 
            }

            questionHeaders.forEach(function (otherHeader) {
                if (otherHeader !== header) {
                    otherHeader.nextElementSibling.style.display = 'none';
                    toggleArrowDisplay(otherHeader, false);
                    otherHeader.classList.remove('hover-style'); 
                }
            });
        });
    });
});