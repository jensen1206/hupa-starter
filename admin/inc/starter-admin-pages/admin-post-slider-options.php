<?php
defined( 'ABSPATH' ) or die();
/**
 * Jens Wiecker PHP Class
 * @package Jens Wiecker WordPress Plugin
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

?>
<div class="wp-bs-starter-wrapper my3">
	<div class="container">
		<div class="card shadow-sm">
			<h5 class="card-header d-flex align-items-center bg-hupa py-4">
				<i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
				<?= __( 'Post Select Slider', 'bootscore' ) ?> </h5>
			<div class="card-body pb-4" style="min-height: 72vh">
				<div class="d-flex align-items-center">
					<h5 class="card-title"><i
							class="hupa-color fa fa-arrow-circle-right"></i> <?= __( 'Post Slider', 'bootscore' ) ?>
						/ <span id="currentSideTitle"><?= __( 'Overview', 'bootscore' ) ?></span>
					</h5>
					<div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
				</div>
				<hr>
                <button class="btn btn-blue" data-bs-toggle="modal"
                        data-bs-target="#carouselModal">
                    <i class="fa fa-plus"></i>
                    &nbsp; <?= __( 'Create new Post-Slider', 'bootscore' ) ?></button>

                <hr>
                <div id="theme-slider-data"></div>

			</div>
		</div>
	</div>

    <!--MODAL-->
    <div class="modal fade" id="sliderModal" tabindex="-1" aria-labelledby="sliderModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="sendAjaxCarouselBtnForm" action="#" method="post">
                    <input type="hidden" name="method" value="add_slider">
                    <div class="modal-header bg-accordion-gray">
                        <h5 class="modal-title" id="carouselModalLabel"><i
                                    class="font-blue fa fa-tasks"></i>&nbsp; <?= __( 'Create new Post-Slider', 'bootscore' ) ?>
                            .</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="inputCarouselName"
                                   class="col-form-label fs-5"><b><?= __( 'Designation', 'bootscore' ) ?>:</b></label>
                            <input type="text" class="form-control" name="bezeichnung"
                                   aria-describedby="inputCarouselHelp" id="inputCarouselName" required>
                            <div id="inputCarouselHelp"
                                 class="form-text"><?= __( 'Enter Post-Slider name (max 50 characters)', 'bootscore' ) ?>.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="text-danger fa fa-times"></i> &nbsp; <?= __( 'Cancel', 'bootscore' ) ?>
                        </button>
                        <button type="submit" class="btn btn-blue btn-sm" data-bs-dismiss="modal">
                            <i class="fa fa-plus"></i>
                            &nbsp; <?= __( 'Create new Post-Slider', 'bootscore' ) ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>