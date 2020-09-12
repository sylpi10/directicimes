
window.onscroll = ()=> {
    backToTop()
};
let backTop = document.querySelector('.back-top');

function backToTop() {
       if (document.body.scrollTop > 180 || document.documentElement.scrollTop > 180) {
        //  backTop.style.display = "block";
         backTop.style.opacity = "1";
       } else {
        // backTop.style.display = "none";
        backTop.style.opacity = "0";
       }
 }

