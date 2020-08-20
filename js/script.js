const title = document.querySelector('.error_title');


//////// Light //////////
document.onmousemove = function(e) {
    let x = e.pageX - window.innerWidth/2;
    let y = e.pageY - window.innerHeight/2;

    title.style.setProperty('--x', x + 'px')
    title.style.setProperty('--y', y + 'px')
}

////////////// Shadow ///////////////////
title.onmousemove = function(e) {
    let x = e.pageX - window.innerWidth/2;
    let y = e.pageY - window.innerHeight/2;

    let rad = Math.atan2(y, x).toFixed(2);
    let length = Math.round(Math.sqrt((Math.pow(x,2))+(Math.pow(y,2)))/10);

    let x_shadow = Math.round(length * Math.cos(rad));
    let y_shadow = Math.round(length * Math.sin(rad));

    title.style.setProperty('--x-shadow', - x_shadow + 'px');
    title.style.setProperty('--y-shadow', - y_shadow + 'px');

};

function validform() {

    var a = document.forms["my-form"]["full-name"].value;
    var b = document.forms["my-form"]["email-address"].value;
    var c = document.forms["my-form"]["username"].value;
    var d = document.forms["my-form"]["permanent-address"].value;
    var e = document.forms["my-form"]["nid-number"].value;

    if (a==null || a=="")
    {
        alert("Please Enter Your Full Name");
        return false;
    }else if (b==null || b=="")
    {
        alert("Please Enter Your Email Address");
        return false;
    }else if (c==null || c=="")
    {
        alert("Please Enter Your Username");
        return false;
    }else if (d==null || d=="")
    {
        alert("Please Enter Your Permanent Address");
        return false;
    }else if (e==null || e=="")
    {
        alert("Please Enter Your NID Number");
        return false;
    }

}