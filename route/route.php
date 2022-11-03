<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::rule('ranking','index/ranking');
Route::rule('clockin','index/clockin');
Route::rule('about','index/about');
Route::rule('notify','index/notify');
Route::rule('verify/:id','index/verify');
Route::rule('api/v1/verifyEmail/:id','index/verifyEmail');
Route::rule('downloadFile/:id','api/downloadFile');

/* API LIST */
Route::rule('api/v1/getCategory','api/getCategory');
Route::rule('api/v1/getChallenges','api/getChallenges');
Route::rule('api/v1/getChallengeInfo/:id','api/getChallengeInfo');
Route::rule('api/v1/getSearchSort','api/getSearchSort');
Route::rule('api/v1/getAllRank','api/getAllRank');
Route::rule('api/v1/getSolveRank','api/getSolveRank');
Route::rule('api/v1/getUserStatus','user/getUserStatus');
Route::rule('api/v1/getUserInfo','user/getUserInfo');
Route::rule('api/v1/getMoneyInfo','user/getMoneyInfo');
Route::rule('api/v1/editUserInfo','user/editUserInfo');
Route::rule('api/v1/getHomeInfo/:id','user/getHomeInfo');
Route::rule('api/v1/notify','push/notify');
Route::rule('api/v1/getAllNotify','api/getAllNotify');
Route::rule('api/v1/push','push/index');

/* POST LIST */
Route::rule('api/v1/postFlag','post/postFlag');
Route::rule('api/v1/buyChallenge','post/buyChallenge');
Route::rule('api/v1/uploadAvatar','admin/user/uploadAvatar');
Route::rule('api/v1/uploadFile','admin/Challenges/uploadFile');

/* USER LIST */
Route::rule('home','user/home');
Route::rule('money','user/money');
Route::rule('user/login','user/login');
Route::rule('user/register','user/register');
Route::rule('user/logout','user/logout');
Route::rule('user/uploadAvatar','user/uploadAvatar');

/* ADMIN LIST */
Route::rule('admin/userList','admin/User/index');
Route::rule('admin/addUser','admin/User/add');
Route::rule('admin/challengeList','admin/Challenges/index');
Route::rule('admin/addChallenge','admin/Challenges/add');
Route::rule('admin/editChallenge','admin/Challenges/edit');
Route::rule('admin/sortList','admin/Sort/index');
Route::rule('admin/notifyList','admin/Notify/index');
Route::rule('admin/Setting','admin/index/setting');

/* USER */
Route::rule('api/v1/editUser','admin/User/editUser');
Route::rule('api/v1/getUserList','admin/User/getUserList');
Route::rule('api/v1/getUserAllInfo','admin/User/getUserAllInfo');
Route::rule('api/v1/deleteUser','admin/User/deleteUser');
Route::rule('api/v1/editUserState','admin/User/editUserState');

/* Challenge */
Route::rule('api/v1/getChallengeList','admin/Challenges/getChallengeList');
Route::rule('api/v1/addChallenge','admin/Challenges/addChallenge');
Route::rule('api/v1/editChallenge','admin/Challenges/editChallenge');
Route::rule('api/v1/deleteChallenge','admin/Challenges/deleteChallenge');
Route::rule('api/v1/getChallengeAllInfo/:id','admin/Challenges/getChallengeAllInfo');

/* SORT */
Route::rule('api/v1/getSortList','admin/Sort/getSortList');
Route::rule('api/v1/editSort','admin/Sort/editSort');
Route::rule('api/v1/deleteSort','admin/Sort/deleteSort');

/* Notify */
Route::rule('api/v1/getNotifyList','admin/Notify/getNotifyList');
Route::rule('api/v1/editNotify','admin/Notify/editNotify');
Route::rule('api/v1/deleteNotify','admin/Notify/deleteNotify');

/* SETTING */
Route::rule('api/v1/getSettingsInfo','admin/index/getSettingsInfo');
Route::rule('api/v1/editSettings','admin/index/editSettings');
