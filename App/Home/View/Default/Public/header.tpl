<div id="header">
	<!--包括可见区域的所有-->
	<div class="header_main">
		<div class="logo">微博系统</div>
		<!--导航-->
		<nav class="nav">
			<ul>
				<li><a href="{:U('Index/index')}" class="selected">首页</a></li>
				<li><a href="#">广场</a></li>
				<li><a href="#">图片</a></li>
				<li><a href="#">找人</a></li>
			</ul>
		</nav>

		<div class="person">
			<ul>
				<!--username栏-->
				<li class="user">
					<!--username数据绑定-->
					<a href="#">{:session('user_auth')['username']}</a>
					<!--提醒数量>0时显示-->
					<!--<gt name="referCount" value="0">
						<div class="refer">
							<span>x</span>
							您有{$referCount}条@提及！
						</div>
					</gt>-->
					<div class="refer">
						<span>x</span>
						您有<b>0</b>条@提及！
					</div>
				</li>
				<!--消息栏-->
				<li class="app">消息
					<!--下拉-->
					<dl class="list">
						<!--提醒-->
						<dd><a href="{:U('Setting/refer')}">@提到我
								<!--提醒数量0到XXXX-->
								<gt name="referCount" value="0">
									<strong style="color:red;">({$referCount})</strong>
									<else/>
									<span>({$referCount})</span>
								</gt>
							</a></dd>
						<dd><a href="#">收到的评论</a></dd>
						<dd><a href="#">发出的评论</a></dd>
						<dd><a href="#">我的私信</a></dd>
						<dd><a href="#">系统消息</a></dd>
						<dd><a href="#" class="line">发私信»</a></dd>
					</dl>
				</li>
				<!--账号管理栏-->
				<li class="app">帐号
					<!--下拉-->
					<dl class="list">
						<dd><a href="{:U('Setting/index')}">个人设置</a></dd>
						<dd><a href="#">排行榜</a></dd>
						<dd><a href="#">申请认证</a></dd>
						<dd><a href="{:U('User/logout')}" class="line">退出»</a></dd>
					</dl>
				</li>

			</ul>
		</div>
		<!--搜索-->
		<div class="search">
			<form method="post" action="#">
				<input type="text" id="search" placeholder="请输入微博关键字">
				<a href="javascript:void(0)"></a>
			</form>
		</div>
	</div>
</div>