var defaultAvatar = 'data:image/png;base64,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';

function openNav() {
    const sidenav = document.getElementById("mySidenav");
    if (sidenav.style.width === "170px") {
        sidenav.style.width = "0";
        sidenav.style.padding = "0";
        sidenav.style.boxShadow = "none";
        sidenav.style.overflowY = "none";
    } else {
        sidenav.style.width = "170px";
        sidenav.style.boxShadow = "0px 0px 5px rgb(0 0 0 / 10%)";
        sidenav.style.overflowY = "auto";
    }
}

$(".close, .bot-close").click(function(){
    history.pushState("", "", window.location.pathname);
});

function getChallengeInfo(res){
    const { id, title, content, solved, download, money, hint } = res.data;
    $(".challenge-info .id").text(id);
    $(".challenge-info .title").text(title);
    $(".challenge-info .content").html(content);
    $("#challengeModal").modal("show");
    $(".challenge-info .form-input").toggle(!solved);
    if (download) {
        if (download == "Not purchased") {
            $("#buyModal #buyData").text("You need to buy this challenge to download the attachment");
            $("#buyModal .btn-primary").text(`${money} Purchase`);
            $(".challenge-info .download").attr({
                "data-toggle": "modal",
                "data-target": "#buyModal",
            });
            $(".challenge-info .download a").removeAttr("target href");
        } else if (download == "Not a file") {
            $(".challenge-info .download a").removeAttr("target href");
            layer.msg('题目附件损坏,请联系管理!', {icon: 0});
        } else {
            $(".challenge-info .download a").attr("href", download);
        }
        $(".challenge-info .download").show();
    } else {
        $(".challenge-info .download").hide();
    }
    if (hint) {
        $("#hintModal #hintData").html(hint);
        $(".challenge-info .hint").show();
    } else {
        $(".challenge-info .hint").hide();
    }
    $("#challengeModal").modal("show");
}

function getChallenges(res) {
    return res.data.data.map(challenge => {
      const { id, title, money, score, solve, category, tags, solved } = challenge;
      return `
        <div class="col-sm-6 col-md-4 col-lg-3">
          ${solved ? '<span class="badge solved badge-success">SOLVED</span>' : ''}
          <div class="i-card-main solve-list">
            <div class="id" style="display:none">${id}</div>
            <div class="title">${title}</div>
            <div class="content">
              <div class="info">
                <span class="money">${money}</span><br>
                <small>Money</small>
              </div>
              <div class="info">
                <span class="score">${score}</span><br>
                <small>Score</small>
              </div>
              <div class="info">
                <span class="solve">${solve}</span><br>
                <small>Solve</small>
              </div>
            </div>
            <div class="tags">
              <span><a href="#" class="badge badge-info">${category}</a></span>
              ${tags.map(tag => `<span><a href="#" class="badge badge-light">${tag}</a></span>`).join('')}
            </div>
          </div>
        </div>`;
    }).join('');
}

function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}

function timestampToTime(timestamp) { 
    let date = new Date(timestamp*1000), 
    Y = date.getFullYear() + '-',
    M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-',
    D = (date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + ' ',
    h = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':',
    m = (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()) + ':',
    s = (date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds());
    return Y + M + D + h + m + s;
}

function getPage(res) {
    if (res.data.last_page <= 1) {
      return;
    }
  
    $('#page').show();
    const sort = $(".sort .active span").attr("id");
    const state = $(".state .active span").attr("id");
    let pageHtml = "";
    const addPageLink = (text, page) => {
      // 如果是上一页，且当前页是第一页，就添加 disabled 类
      if (text == "Previous" && res.data.current_page == 1) {
        pageHtml += `<li class="page-item disabled"><a class="page-link" href="#">${text}</a></li>`;
        return;
      }
      // 如果是下一页，且当前页是最后一页，就添加 disabled 类
      if (text == "Next" && res.data.current_page == res.data.last_page) {
        pageHtml += `<li class="page-item disabled"><a class="page-link" href="#">${text}</a></li>`;
        return;
      }
      // 如果是数字页，且是当前页，就添加 active 类
      if (typeof text == "number" && text == res.data.current_page) {
        pageHtml += `<li class="page-item active"><a class="page-link" href="#" onclick="getSearchSort('${sort}', '${state}', ${page})">${text}</a></li>`;
        return;
      }
      // 其他情况就添加普通的页码链接
      pageHtml += `<li class="page-item"><a class="page-link" href="#" onclick="getSearchSort('${sort}', '${state}', ${page})">${text}</a></li>`;
    };
  
    addPageLink("Previous", res.data.current_page - 1);
    for (let i = 1; i <= res.data.last_page; i++) {
      addPageLink(i, i);
    }
    addPageLink("Next", res.data.current_page + 1);
  
    $(".pagination").html(pageHtml);
}

function pages(res) {
    if(res.data.last_page > 1){
        $('#page').show();
        var pageHtml = "";
        if(res.data.current_page == 1){
            pageHtml += "<li class='page-item disabled'><a class='page-link' href='#''>Previous</a></li>";
        }else{
            pageHtml += "<li class='page-item'><a class='page-link' href='?page=" + (parseInt(res.data.current_page) - 1) + "''>Previous</a></li>";
        }
        for(var i = 1; i <= res.data.last_page; i++){
            if(i == res.data.current_page){
                pageHtml += "<li class='page-item active'><a class='page-link' href='?page=" + i + "'>" + i + "</a></li>";
            }else{
                pageHtml += "<li class='page-item'><a class='page-link' href='?page=" + i + "'>" + i + "</a></li>";
            }
        }
        if(res.data.current_page == res.data.last_page){
            pageHtml += "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
        }else{
            pageHtml += "<li class='page-item'><a class='page-link' href='?page=" + (parseInt(res.data.current_page) + 1) + "'>Next</a></li>";
        }
        $(".pagination").html(pageHtml);
    }
}

function getCookie(name) {
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    if (arr = document.cookie.match(reg)){
        return unescape(arr[2]);
    }else{
        return null;
    }
}

function postData() {
    var formData = new FormData();
    formData.append("file", $("#uploadImage")[0].files[0]);
    $.ajax({
        url: '/user/uploadAvatar',
        type: "post",
        data: formData,
        processData: false,
        contentType: false, 
        dataType: 'text',
        success: function (res) {         
            var data = JSON.parse(res);
            if (data.code == 200) {
                layer.msg(data.msg, {icon: 1});
                $('#editUser img').attr('src', data.data.url);
            }
        }
    });
}

getCookie('islogin') == 1 ? $.get('/api/v1/getUserStatus', function(res) {
    if (res.code != 200) return;
    $('.islogin').show();
    $('.islogin #username').text('Hi, ' + res.data.username);
    res.data.avatar && $('.islogin img').attr('src', res.data.avatar);
    res.data.door && $('.islogin #door').html(res.data.door).show();
    $('.more .item span').html(res.data.type);
    $('.islogin #userhome a').attr('href', '/home/' + res.data.userid);
}) : $('.nologin').show();


$('#logout').click(function () {
    $.get('/user/logout', function (res) {
        if (res.code == 200) {
            var keys = document.cookie.match(/[^ =;]+(?=\=)/g);
            if (keys) {
                for (var i = keys.length; i--;)
                    document.cookie = keys[i] + '=0;expires=' + new Date(0).toUTCString()
            }
            layer.msg(res.msg, {icon: 1});
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        }else{
            layer.msg(res.msg, {icon: 2});
        }
    });
});

$('#edit').click(function () {
    $('.loading').show();
    $.get('/api/v1/getUserInfo', function (res) {
        if (res.code === 200) {
            const data = res.data;
            const editUser = $('#editUser');
            editUser.find('.name').text(data.username);
            if (data.avatar) {
                editUser.find('img').attr('src', data.avatar);
            }
            editUser.find('input[name="username"]').val(data.nickname);
            editUser.find('input[name="email"]').val(data.email);
            editUser.find('input[name="website"]').val(data.website);
            editUser.find('input[name="content"]').val(data.content);
            if (data.verify) {
                $('#email span').removeClass('badge-warning').addClass('badge-success').text('已验证').removeAttr('id');
            }
        } else {
            layer.msg(res.msg, {icon: 2});
        }
        $('.loading').hide();
    });
});


$('#editUser input[name="new_password"]').blur(function () {
    var password = $('#editUser input[name="password"]').val();
    var new_password = $('#editUser input[name="new_password"]').val();
    if (!password && new_password) {
        layer.msg('请先输入旧密码', {icon: 2});
    }
});

$('#userModal').on('click', '#update', function () {
    var username = $('#editUser input[name="username"]').val();
    var password = $('#editUser input[name="password"]').val();
    var new_password = $('#editUser input[name="new_password"]').val();
    var email = $('#editUser input[name="email"]').val();
    var website = $('#editUser input[name="website"]').val();
    var content = $('#editUser input[name="content"]').val();
    $.post('/api/v1/editUserInfo', {
        username: username,
        password: password,
        new_password: new_password,
        email: email,
        website: website,
        content: content
    }, function (res) {
        if (res.code == 200) {
            layer.msg(res.msg, {icon: 1});
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        }else{
            layer.msg(res.msg, {icon: 2});
        }
    });
});

$(document).on('click', '#clockin, .dropdown-item #clockin', function () {
    $.get('/clockin', function (res) {
        if (res.code == 200) {
            layer.msg(res.msg, {icon: 1});
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        }else{
            layer.msg(res.msg, {icon: 2});
        }
    });
});

$(document).on('click', '#sendEmail', function () {
    layer.confirm('确定要发送激活邮件吗？', {
        btn: ['确定','取消']
    }, function(){
        layer.msg('邮件发送中...', {icon: 16,shade: 0.01});
        $.get('/api/v1/sendEmail', function (res) {
            if (res.code == 200) {
                layer.msg(res.msg, {icon: 1});
            }else{
                layer.msg(res.msg, {icon: 2});
            }
        });
    });
});