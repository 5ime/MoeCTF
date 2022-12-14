function ifPassword(){
    var one = document.getElementById("password").value;
    let regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{8,31}$/;

    if (!regex.test(one)) {
        document.getElementById("pass-tips").style.display = "inline";
    }else{
        document.getElementById("pass-tips").style.display = "none";
    }

    var two = document.getElementById("topassword").value;
    console.log(two);
    if (one != two && two != '' ){
        document.getElementById("topass-tips").style.display = "inline";
    }else{
        document.getElementById("topass-tips").style.display = "none";
    }
}
 
  //获取文本框,注册失去焦点的事件
  document.getElementById("email").onblur = function () {
    //判断这个文本框中输入的是不是邮箱
    let regex = /^[0-9a-zA-Z_.-]+[@][0-9a-zA-Z_.-]+([.][a-zA-Z]+){1,2}$/;
    if (regex.test(this.value)) {
        document.getElementById("mail—tips").style.display = "none";
    } else {
        document.getElementById("mail—tips").style.display = "inline";
    }
  };
