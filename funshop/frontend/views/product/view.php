<?php
/* @var $this yii\web\View */
$this->registerCssFile('@web/css/product.css', ['depends' => \frontend\assets\AppAsset::className()]);

$arrayPath = \common\models\Category::getCatalogPath($model->category_id, $allCategory);
foreach ($arrayPath as $path) {
    $category = \common\models\Category::findOne($path);
    $this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['/category/view', 'id' => $category->id]];
}
$this->params['breadcrumbs'][] = $model->name;

$countCommentsPassed = count($model->commentsPassed);
$starGoodPercent = $starNormalPercent = $starBadPercent = 0;
if ($countCommentsPassed > 0) {
    $starGoodPercent = Yii::$app->formatter->asPercent(count($model->commentsPassedGood) / $countCommentsPassed);
    $starNormalPercent = Yii::$app->formatter->asPercent(count($model->commentsPassedNormal) / $countCommentsPassed);
    $starBadPercent = Yii::$app->formatter->asPercent(count($model->commentsPassedBad) / $countCommentsPassed);
}
?>

<div id="wrapper">
<!-- breadcrumbs -->
<div class="breadcrumbs cle">
    <?= \yii\widgets\Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'options' => ['class' => 'menus'],
        'tag' => 'div',
        'itemTemplate' => ' &gt; {link}',
        'activeItemTemplate' => ' &gt; {link}',
    ]) ?>
</div>
<!-- product detail -->
<div class="detail cle">
    <!-- 图片 -->
    <div class="detail_img" id="detail_img">
        <div class="pic_view"> <img id="pic-view" alt="<?= $model->name ?>" src="<?= $model->image ?>" /> </div>
        <div class="item-thumbs" id="item-thumbs">
            <ul class="cle">
                <?php foreach ($model->productImagesSort as $image) { ?>
                <li> <img alt="<?= $model->name ?>" src="<?= $image->thumb ?>" data-view="<?= $image->image ?>" /> </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="item-info" id="item-info">
        <dl>
            <dt class="product_name">
                <h1><?= $model->name ?></h1>
                <p class="desc"> <span class="gray"><?= $model->brief ?></span> </p>
            </dt>
            <dd class="property">
                <ul>
                    <li> <span class="lbl">市场价</span> <em class="rmb">¥</em> <em class="cancel">
                            <?= $model->market_price ? $model->market_price : (int)$model->price * 1.3 ?>
                        </em> </li>
                    <li style="z-index: 100;"> <span class="lbl">商城价</span>
                        <span class="unit">
                            <em class="rmb red">¥</em> <strong class="nala_price red"><?= $model->price ?></strong> </span> <span class="unit">节省<?= $model->market_price ? $model->market_price - $model->price : (int)$model->price * 1.3 - $model->price ?>元
                        </span>
                    </li>
                    <li><span class="lbl">积&nbsp;&nbsp;&nbsp;分</span><span>可获<em class="red"><?= (int)$model->price ?></em>积分（抵￥<?= Yii::$app->formatter->asDecimal((int)$model->price / 100, 2) ?>）</span></li>
                    <li><span class="lbl">销&nbsp;&nbsp;&nbsp;量</span><span>最近售出<em class="red"><?= $model->sales ?></em>件</span></li>
                    <li><span class="lbl">评&nbsp;&nbsp;&nbsp;价</span><span><cite class="ping_star"><i style="width:<?= Yii::$app->formatter->asPercent($model->star / 5) ?>;"></i></cite><em class="red"><?= $model->star ?>分（<a id="pjxqitem_trig" href="#pjxqitem">已有<?= $countCommentsPassed ?>人评价</a>）</em></span></li>
                </ul>
            </dd>
            <dd class="tobuy-box cle shop_ewm">
                <ul class="sku">
                    <li class="skunum_li cle"> <span class="lbl">数&nbsp;&nbsp;&nbsp;量</span>
                        <div id="skunum" class="skunum"> <span title="减少1个数量" class="minus"><i class="iconfont">-</i></span>
                            <input id="input-buy-number" type="text" min="1" max="411" value="1">
                            <span title="增加1个数量" class="add"><i class="iconfont">+</i></span> <cite class="storage"> 件（库存<em id="storage"><?= $model->stock ?></em>件) </cite> </div>
                        <input type="hidden" value="2031196" id="skuid">
                    </li>
                    <!-- 购买——正常按钮时  -->
                    <li class="add_cart_li"></li>
                </ul>
            </dd>
        </dl>
        <!-- 承诺  -->
        <div id="privileges" class="privileges cle">
            <ul class="cle">
                <li><a href="#" rel="nofollow"><i class="iconfont">*</i>100%正品</a> </li>
                <li><a href="#" rel="nofollow"><i class="iconfont">*</i>30天无理由退货</a> </li>
                <li><a href="#" rel="nofollow"><i class="iconfont">*</i>满99包邮</a> </li>
                <li><a href="#" rel="nofollow"><i class="iconfont">*</i>购物车换购</a> </li>
            </ul>
        </div>
    </div>

    <div class="seemore_items" id="seemore_items">
        <h3>同类热销</h3>
        <div class="bd">
            <ul>
                <?php foreach ($sameCategoryProducts as $item) { ?>
                <li>
                    <a href="<?= Yii::$app->urlManager->createUrl(['product/view', 'id' => $item->id]) ?>" target="_blank" title="<?= $item->name ?>"> <img alt="<?= $item->name ?>" src="<?= $item->thumb ?>">
                        <p class="price"><?= $item->price ?></p>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="detail_bgcolor">
<div class="z-detail-box cle">
<div class="z-detail-left">

<div class="tabs_bar_warp">
    <div class="tabs_bar" id="tabs_bar">
        <ul>
            <li class="current_select"> <a class="spxqitem" rel="nofollow" href="javascript:;" >商品详情</a> </li>
            <li class=""><a class="pjxqitem" href="javascript:;" name="pjxqitem" rel="nofollow">评价详情(<em><?= count($model->commentsPassed) ?></em>)</a></li>
            <li class=""><a class="askitem" href="javascript:;" rel="nofollow" >购买咨询(<em><?= count($model->consultationsPassed) ?></em>)</a></li>
            <li class="tab-buy" id="tab-buy">￥<strong>46.50</strong><a class="btn" href="javascript:;">购 买</a></li>
        </ul>
    </div>
</div>
<div class="product_tabs">
<div class="sectionbox z-box" id="spxqitem" >
    <!-- 详情页顶部banner cms: detail_top_ban -->
    <div class="spxq_main">
        <div class="spxq_top"></div>
        <?php if ($model->brief) { ?>
            <h2 class="tit tit1"><span>产品介绍</span></h2>
            <div class="nala_say"> <?= $model->brief ?></div>
        <?php } ?>
        <h2 class="tit tit3"><span>产品描述</span></h2>
        <div class="spxq_dec">
            <p> <?= Yii::$app->formatter->asHtml($model->content) ?></p>
        </div>
    </div>
</div>
<!-- 评价详情-->
<!--评价详情-->
<div class="z-box sectionbox" id="pjxqitem">
<div class="z-detail-point-box cle">
    <div class="z-detail-point-box-left">
        <div class="z-points">
            <div class="z-all-points"> <b><?= $model->star ?></b> /5 </div>
            <div class="big_star"><i style="width:97%;"></i></div>
            <div class="z-all-people"> 共 <em><?= $countCommentsPassed ?></em> 人打分 </div>
        </div>
        <div class="z-point-list">
            <ul id="min_points">
                <li>
                    <label>好评：</label>
                    <p> <span style="width: <?= $starGoodPercent ?>"></span> </p>
                    <em><?= $starGoodPercent ?></em>
                </li>
                <li data-point="5.0">
                    <label>中评：</label>
                    <p> <span style="width: <?= $starNormalPercent ?>"></span> </p>
                    <em><?= $starNormalPercent ?></em> </li>
                <li data-point="5.0">
                    <label>差评：</label>
                    <p> <span style="width: <?= $starBadPercent ?>"></span> </p>
                    <em><?= $starBadPercent ?></em> </li>
            </ul>
        </div>
    </div>
    <div class="z-detail-point-box-right">
        <div>对自己使用过的商品进行评价，它将成为顾客们的购买参考依据。</div>
        <div>每个商品评价成功立送5个积分。</div>
        <div class="good_com_box"> <a href="javascript:;" class="good_com" id="show_good_com">精华评价</a>前十位送100积分。
            <div class="good_com_tips" id="good_com_tips"> <em class="z-arrow"><i class="z-arrow-line">◆</i> <i class="z-arrow-bg">◆</i></em> 评价50字以上并上传真实照片即有机会被评为精华评价 </div>
            <a href="<?= Yii::$app->urlManager->createUrl(['/comment']) ?>" id="go_com" target="_blank" class="btn go_btn"  rel="nofollow">我要评价</a> </div>
    </div>
</div>
<div class="z-detail-com-box"  id="com-list">
    <div class="z-com-box-head">
        全部评价（<?= $countCommentsPassed ?>）
    </div>
    <div class="z-com-list">
    </div>
</div>
</div>
<!-- 评价详情 end-->
<!-- 购买咨询-->
<div id="askitem" class="sectionbox z-box" name="askitem">
    <h2 class="tit tit13"><span>购买咨询</span></h2>
    <div class="content">
        <div class="ask-form cle">
            <div class="fl">
                <textarea id="consultation-question"></textarea>
                <a href="javascript:;" class="graybtn" id="consultation-btn">发表咨询</a>
            </div>
            <div class="fr">
                <p><b>温馨提示:</b>您可在购买前对产品包装、颜色、运输、库存等方面进行咨询，我们有专人进行回复！因厂家随时会更改一些产品的包装、颜色、产地等参数，所以该回复仅在当时对提问者有效，其他网友仅供参考！</p>
            </div>
        </div>
        <div class="ask-list" id="ask-list">
        </div>
    </div>
</div>

<!-- tab栏 -->
</div>
</div>
<!--详情页右侧栏-->
<div class="z-detail-right">
    <div class="right_planes">
        <div class="right_box hot_items">
            <div class="hd">热销商品</div>
            <div class="bd">
                <ul class="cle">
                    <?php foreach ($sameRootCategoryProducts as $item) { ?>
                    <li>
                        <span class="productimg">
                            <a href="<?= Yii::$app->urlManager->createUrl(['product/view', 'id' => $item->id]) ?>" target="_blank">
                                <img width="150" height="150" title="<?= $item->name ?>" alt="<?= $item->name ?>" src="<?= $item->thumb ?>">
                            </a>
                        </span>
                        <span class="productname">
                            <a href="<?= Yii::$app->urlManager->createUrl(['product/view', 'id' => $item->id]) ?>" target="_blank"><?= $item->name ?></a>
                        </span>
                        <span class="nalaprice">￥<b><?= $item->price ?></b></span>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="right_box hot_items">
            <div class="hd">浏览记录</div>
            <div class="bd">
                <ul class="cle">
                    <?php foreach ($historyProducts as $item) { ?>
                        <li>
                        <span class="productimg">
                            <a href="<?= Yii::$app->urlManager->createUrl(['product/view', 'id' => $item->id]) ?>" target="_blank">
                                <img width="150" height="150" title="<?= $item->name ?>" alt="<?= $item->name ?>" src="<?= $item->thumb ?>">
                            </a>
                        </span>
                        <span class="productname">
                            <a href="<?= Yii::$app->urlManager->createUrl(['product/view', 'id' => $item->id]) ?>" target="_blank"><?= $item->name ?></a>
                        </span>
                            <span class="nalaprice">￥<b><?= $item->price ?></b></span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
</div>

</div>
<?php
$isFavorite = 0;
if (!Yii::$app->user->isGuest) {
    $favorite = \common\models\Favorite::find()->where(['user_id' => Yii::$app->user->id, 'product_id' => $model->id])->one();
    if ($favorite) {
        $isFavorite = 1;
    }
}
$urlAddToCart = Yii::$app->urlManager->createUrl(['cart/add-to-cart']);
$urlFavorite = Yii::$app->urlManager->createUrl(['product/favorite']);
$urlLogin = Yii::$app->urlManager->createUrl(['site/login']);
$urlConsultationAdd = Yii::$app->urlManager->createUrl(['consultation/ajax-add']);
$urlComment = Yii::$app->urlManager->createUrl(['product/comment']);
$urlConsultation = Yii::$app->urlManager->createUrl(['product/consultation']);
$this->registerJs('
var product = {productId:' . $model->id . ', stock:' . $model->stock . ', csrf:"' . Yii::$app->request->getCsrfToken() . '"};
var user = {id:' . (Yii::$app->user->isGuest ? 0 : Yii::$app->user->id) . ', favorite:' . $isFavorite . '};
var urlCartAdd = "' . Yii::$app->urlManager->createUrl(['cart/ajax-add']) . '";
');
$js = <<<JS
var view = $("#pic-view");
var thumb = $("#item-thumbs");
var clone = thumb.find('img').eq(0).clone();
var len = thumb.find('li').length;
var _left = 66;
var  show = null;
clone.insertAfter(view);
thumb.append('<div class="arrow"><i class="icon iconfont">^</i></div>');
var arrow = thumb.find('div.arrow');
arrow.css({
    'left': _left
}).show();
if (len < 5) {
    var l = 5 - len;
    _left += l * 33;
    arrow.css({
        'left': _left
    }).show();
    thumb.find('ul').css({
        'width': 66 * len
    });
}
thumb.find('li').eq(0).addClass('current');
thumb.find('li').mouseenter(function(){
    var _li = $(this);
    if (_li.hasClass('current')) {
        return false;
    }
    var _i = _li.index();
    var _img = _li.find('img');
    _ssrc = _img.attr('src');
    _bsrc = _img.data('view');

    arrow.css({
        'left': _i * 66 + _left
    });
    _li.addClass('current').siblings('li').removeClass('current');
    clone.attr('src', _ssrc);
    view.attr('src', _bsrc);

});

if (product.stock > 0) {
    $("li.add_cart_li").html('<a href="javascript:;" class="btn" id="buy_btn"><i class="glyphicon glyphicon-shopping-cart"></i> 加入购物车</a>')
} else {
    $("li.add_cart_li").html('<span>暂时无货</span>')
}
if (user.favorite > 0) {
    $("li.add_cart_li").append('<a href="javascript:;" class="graybtn" id="has_fav_btn"><i class="glyphicon glyphicon-heart"></i> 已收藏</a>');
} else {
    $("li.add_cart_li").append('<a href="javascript:;" class="graybtn" id="fav_btn"><i class="glyphicon glyphicon-heart-empty"></i> 收藏</a>');
}

$("#pjxqitem").hide();
$("#askitem").hide();

jQuery(".minus").click(function(){
    $("#input-buy-number").val(parseInt($("#input-buy-number").val()) - 1);
    if (parseInt($("#input-buy-number").val()) < 1 ) {
        $("#input-buy-number").val(1);
    }
});//end click
jQuery(".add").click(function(){
    $("#input-buy-number").val(parseInt($("#input-buy-number").val()) + 1);
    if (parseInt($("#input-buy-number").val()) > product.stock ) {
        $("#input-buy-number").val(product.stock);
    }
});//end click


jQuery("#buy_btn").click(function(){
    var number = $("#input-buy-number").val();
    if (number > product.stock) {
        alert('购买数量超过库存或限购数');
    } else {
        $(this).html('<img src="/images/loading.gif" /> 加入购物车');
        param = {
            productId : product.productId,
            number : $("#input-buy-number").val(),
            _csrf : product.csrf
        };
        $.post(urlCartAdd, param, function(data) {
            if (data.status > 0) {
                location.href = "{$urlAddToCart}?id=" + product.productId;
            }
        }, "json");
    }

});
$("#fav_btn").click(function(){
    if (user.id > 0) {
        $.get("{$urlFavorite}?id=" + product.productId, function(data, status) {
            if (status == "success") {
                if (data.status) {
                    $("#fav_btn").html('<i class="glyphicon glyphicon-heart"></i>已收藏</a>');
                }
            }
        }, "json");
    } else {
        location.href = '{$urlLogin}?returnUrl=' + window.location.href;
    }
});


$(".spxqitem").click(function(){
    $("#tabs_bar li").removeClass('current_select');
    $(this).parent().addClass('current_select');
    $("#spxqitem").show();
    $("#pjxqitem").hide();
    $("#askitem").hide();
});
$(".pjxqitem").click(function(){
    $("#tabs_bar li").removeClass('current_select');
    $(this).parent().addClass('current_select');
    $("#spxqitem").hide();
    $("#pjxqitem").show();
    $("#askitem").hide();
});
$(".askitem").click(function(){
    $("#tabs_bar li").removeClass('current_select');
    $(this).parent().addClass('current_select');
    $("#spxqitem").hide();
    $("#pjxqitem").hide();
    $("#askitem").show();
});

$.ajax({
    url: "{$urlComment}?productId=" + {$model->id},
    type: "GET",
    dataType: "html",
    success: function(data){
        $('.z-com-list').html(data);
    }
}).fail(function(){
        alert("Error");
});

$('.z-com-list').on('click', '.pagination a', function(e){
    e.preventDefault();
    $.ajax({
        url: $(this).attr('href'),
        type: "GET",
        dataType: "html",
        success: function(data){
            $('.z-com-list').html(data);
        }

    }).fail(function(){
            alert("Error");
    });

});

$('.z-com-list').on('click', 'a.up', function(e){
    var up = $(this);
    var link = $(this).data('link');
    $.get(link, function(data, status) {
        if (status == "success") {
            //alert(data.up);
            up.html("赞 ( <i>" + data.up + "</i> )");
        }
    }, 'json');
});

$("#consultation-btn").click(function(){
    if (user.id > 0) {
        param = {
            productId : product.productId,
            question : $("#consultation-question").val(),
            _csrf : product.csrf
        };
        $.post("{$urlConsultationAdd}", param, function(data) {
            if (data.status > 0) {
                alert("你的咨询已提交成功，我们会尽快回复你的哦。");
                $("#consultation-question").val('');
            }
        }, "json");
    } else {
        location.href = '$urlLogin?returnUrl=' + window.location.href;
    }
});


$.ajax({
    url: "{$urlConsultation}?productId=" + {$model->id},
    type: "GET",
    dataType: "html",
    success: function(data){
        $('.ask-list').html(data);
    }
}).fail(function(){
        alert("Error");
});

$('.ask-list').on('click', '.pagination a', function(e){
    e.preventDefault();
    $.ajax({
        url: $(this).attr('href'),
        type: "GET",
        dataType: "html",
        success: function(data){
            $('.ask-list').html(data);
        }

    }).fail(function(){
        alert("Error");
    });

});

JS;

$this->registerJs($js);
?>