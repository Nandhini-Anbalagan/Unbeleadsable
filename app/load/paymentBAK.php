<ul class="nav nav-tabs tabs userTab">
    <li class="active tab">
        <a href="#sub" data-toggle="sub" aria-expanded="false">
            <span class="visible-xs"><i class="fa fa-list"></i></span>
            <span class="hidden-xs"><?php echo $tr['subscription_payment'] ?>(s)</span>
        </a>
    </li>
    <li class="tab">
        <a href="#ad" data-toggle="ad" aria-expanded="false">
            <span class="visible-xs"><i class="fa fa-question"></i></span>
            <span class="hidden-xs"><?php echo $tr['budget_payment'] ?>(s)</span>
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="sub">
        <h3><?php echo $tr['history_subscription_title'] ?></h3>
        <div class="table-responsive">
            <table class="table table-hover mails m-0 table table-actions-bar">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th><?php echo $tr['amount'] ?></th>
                        <th>Description</th>
                        <th><?php echo $tr['status'] ?></th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php for($i=0; $i<10; $i++){ ?>
                    <tr>
                        <td>Apr. 22, 2016</td>
                        <td>$199.00</td>
                        <td>paying for Renewal (immo@kwdyn...)</td>
                        <td>Successful</td>
                        <td>
                            <a href=""><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>&nbsp;
                            <a href=""><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;
                            <a href=""><i class="fa fa-envelope" aria-hidden="true"></i></a>&nbsp;
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane" id="ad">
        <h3><?php echo $tr['budget_payment'] ?>
        <small>(<?php echo $tr['history_ad_sub_title'] ?>
        May. 24, 2016 for $206.00)</small>
        </h3>
        <div class="table-responsive">
            <table class="table table-hover mails m-0 table table-actions-bar">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php for($i=0; $i<10; $i++){ ?>
                    <tr>
                        <td>Apr. 22, 2016</td>
                        <td>$199.00</td>
                        <td>paying for Renewal (immo@kwdyn...)</td>
                        <td>Successful</td>
                        <td>
                            <a href=""><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>&nbsp;
                            <a href=""><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;
                            <a href=""><i class="fa fa-envelope" aria-hidden="true"></i></a>&nbsp;
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
