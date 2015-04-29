<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
    <h3>日报统计</h3>
    <div class="searchArea">
        <p class="left" >
            <a href="javascript:history.go(-1)" class="btn btn-success btn-sm">返回上页</a>
        </p>
        <div class="right">
        </div>
    </div>
</div>

<table class="table table-bordered table-condensed">
    <thead>
        <tr class="active">
            <th style="width: 100px" >编号</th>
            <th style="width: 200px" >姓名</th>
            <th style="width: 300px" >邮箱</th>
            <th style="width: 100px" >填写次数</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($infos as $index => $info):?>
        <tr>
            <td><?php echo $info['id']?></td>
            <td><?php echo $info['realname']?></td>
            <td><?php echo $info['username']?></td>
            <td><?php echo $info['count']?></td>
        </tr>
<?php endforeach;?>
    </tbody>
</table>
<?php $this->renderPartial('/_include/footer');?>