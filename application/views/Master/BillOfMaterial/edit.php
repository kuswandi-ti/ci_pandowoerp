<style>
	.tree {
		--spacing : 1.5rem;
		--radius  : 7px;
	}

	.tree li {
		display      : block;
		position     : relative;
		padding-left : calc(2 * var(--spacing) - var(--radius) - 2px);
	}

	.tree ul {
		margin-left  : calc(var(--radius) - var(--spacing));
		padding-left : 0;
	}

	.tree ul li {
		border-left : 2px solid #ddd;
	}

	.tree ul li:last-child {
		border-color : transparent;
	}

	.tree ul li::before {
		content      : '';
		display      : block;
		position     : absolute;
		top          : calc(var(--spacing) / -2);
		left         : -2px;
		width        : calc(var(--spacing) + 2px);
		height       : calc(var(--spacing) + 1px);
		border       : solid #ddd;
		border-width : 0 0 2px 2px;
	}

	.tree summary {
		display : block;
		cursor  : pointer;
	}

	.tree summary::marker,
	.tree summary::-webkit-details-marker {
		display : none;
	}

	.tree summary:focus {
		outline : none;
	}

	.tree summary:focus-visible {
		outline : 1px dotted #000;
	}

	.tree li::after,
	.tree summary::before {
		content       : '';
		display       : block;
		position      : absolute;
		top           : calc(var(--spacing) / 2 - var(--radius));
		left          : calc(var(--spacing) - var(--radius) - 1px);
		width         : calc(2 * var(--radius));
		height        : calc(2 * var(--radius));
		border-radius : 50%;
		background    : #ddd;
	}

	.tree summary::before {
		z-index    : 1;
		background : #696 url('expand-collapse.svg') 0 0;
	}

	.tree details[open] > summary::before {
		background-position : calc(-2 * var(--radius)) 0;
	}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/BillOfMaterial/') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
						<input type="hidden" name="sysid" id="sysid" value="<?= $RowData->SysId ?>">						

						<div class="row">
                            <div class="col-lg-12 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Detail :</label>
								<?php echo $bom_tree; ?>
                            </div>
                        </div>
                    </div>
            	</form>            
        	</div>
    	</div>
	</div>
</div>

<div class="modal fade" id="modal_add" style="z-index: 1050 !important;" aria-labelledby="Label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Item Material</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12 col-sm-12 form-group">
						<input type="hidden" name="id_parent" id="id_parent">
						<input type="hidden" name="no_bom" id="no_bom">

						<label style="font-weight: 500;">Item Material :</label>
						<select class="form-control form-control-sm select2" name="id_item" id="id_item" required>
							<?php foreach ($item_non_fg->result() as $row) : ?>
								<option value="<?= $row->SysId ?>"><?= $row->Item_Code ?> - <?= $row->Item_Name ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-sm-12 form-group">
						<label style="font-weight: 500;">Qty :</label>
						<input type="text" class="form-control form-control-sm" name="qty" id="qty" placeholder="0" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" href="#" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save</button>
			</div>
		</div>
	</div>
</div>
