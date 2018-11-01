<!--评论显示-->
<ol class="comment_list">
  <volist name="getList" id="obj">
    <li>
      <!--域名存在性判断-->
      <empty name="obj.domain">
        <a href="{:U('Space/index', array('id'=>$obj['uid']))}" target="_blank">{$obj.username}</a>
        <else/>
        <a href="__ROOT__/i/{$obj.domain}" target="_blank">{$obj.username}</a>
      </empty>
      <!--评论内容-->
      ：{$obj.content}
    </li>
    <!--评论时间-->
    <li class="line">{$obj.time}</li>
  </volist>
</ol>
<!--评论分页-->
<div class="page">
  <for start="1" end="$total+1">
    <a href="javascript:void(0)" page="{$i}" class="page_comment {$page == $i ? 'select' : ''}">{$i}</a>
  </for>
</div>