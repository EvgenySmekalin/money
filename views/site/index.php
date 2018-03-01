<?php
use yii\grid\GridView;
$this->title = 'Balance';
?>
<style>
    table tbody tr > td:last-child {
        text-align: right;
    }

	
</style>

<div class="body-content">
    <div class="row">
        <div class="col-lg-12">
            <h3>Users balance list</h3>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'columns' => [
                    [
                        'label' => 'Nickname',
                        'attribute' => 'nickname',
                    ],
                    [
                        'label' => 'Balance',
                        'attribute' => 'balance',
                    ],

                ],
            ]); ?>


        </div>
    </div>
</div>
