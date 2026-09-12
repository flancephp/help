if(window.location.search.includes("success=true")){

    const cleanUrl = window.location.origin + window.location.pathname;

    window.history.replaceState({}, document.title, cleanUrl);

}


// No longer needed - using simple PHP form instead
setTimeout(() => {
    document.getElementById("submitmsg").style.display = "none";
}, 3000);

// const msg = document.getElementById("submitmsg");

// if(msg){
//     setTimeout(() => {
//         msg.style.display = "none";
//     }, 3000);
// }