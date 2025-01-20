<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard PT. <?= $this->config->item('company_name') ?></h1>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
        <div class="inner" id="sum-tot-receive">
          <h3>
            <?php if (!empty($data_today_receive->kubikasi)) : ?>
              <?= floatval($data_today_receive->kubikasi) ?> <sup style="font-size: 20px">(M³)</sup>
            <?php else : ?>
              <?= '0.00' ?> <sup style="font-size: 20px">(M³)</sup>
            <?php endif; ?>
          </h3>
          <p style="font-weight: bold;">Total penerimaan kayu</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="#" class="small-box-footer"><i class="fas fa-calendar-alt"></i> &nbsp; <?= date('d F Y') ?></a>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>
            <?php if (!empty($data_today_into_oven->kubikasi)) : ?>
              <?= floatval($data_today_into_oven->kubikasi) ?> <sup style="font-size: 20px">(M³)</sup>
            <?php else : ?>
              <?= '0.00' ?> <sup style="font-size: 20px">(M³)</sup>
            <?php endif; ?>
          </h3>
          <p style="font-weight: bold;">Material Masuk KD</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="#" class="small-box-footer"><i class="fas fa-calendar-alt"></i> &nbsp; <?= date('d F Y') ?></a>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>
            <?php if (!empty($data_today_out_oven->kubikasi)) : ?>
              <?= floatval($data_today_out_oven->kubikasi) ?> <sup style="font-size: 20px">(M³)</sup>
            <?php else : ?>
              <?= '0.00' ?> <sup style="font-size: 20px">(M³)</sup>
            <?php endif; ?>
          </h3>
          <p style="font-weight: bold;">Material Selesai KD</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="#" class="small-box-footer"><i class="fas fa-calendar-alt"></i> &nbsp; <?= date('d F Y') ?></a>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
        <div class="inner">
          <h3>
            <?php if (!empty($data_today_alloc_prd->kubikasi)) : ?>
              <?= floatval($data_today_alloc_prd->kubikasi) ?> <sup style="font-size: 20px">(M³)</sup>
            <?php else : ?>
              <?= '0.00' ?> <sup style="font-size: 20px">(M³)</sup>
            <?php endif; ?>
          </h3>
          <p style="font-weight: bold;">Alokasi Ke Produksi</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="#" class="small-box-footer"><i class="fas fa-calendar-alt"></i> &nbsp; <?= date('d F Y') ?></a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-6 col-lg-6" id="column--1">
      <div class="d-flex align-items-center">
        <strong class="blink_me">Loading...</strong>
        <div class="spinner-border text-primary ml-auto" role="status" aria-hidden="true"></div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-6" id="column--2">
      <div class="d-flex align-items-center">
        <strong class="blink_me">Loading...</strong>
        <div class="spinner-border text-primary ml-auto" role="status" aria-hidden="true"></div>
      </div>
    </div>
  </div>
</div>