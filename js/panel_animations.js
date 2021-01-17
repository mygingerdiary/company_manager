const panel_buttons = document.querySelectorAll(".panel-button");

panel_buttons.forEach(function (button) {
    button.addEventListener("mouseenter", function (event) {
        TweenMax.to(event.target, 0.3, {scale: 1.06, ease: Circ.easeOut});
    });
    button.addEventListener("mouseleave", function (event) {
        TweenMax.to(event.target, 0.3, {scale: 1.0, ease: Circ.easeIn});
    });
});
