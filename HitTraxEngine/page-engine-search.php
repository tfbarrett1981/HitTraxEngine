<?php 
/**
 * Template Name: Search Engine
 *
 * @package WordPress
 * @subpackage HitTrax Engine
 * @since HitTrax Engine 1.0
 */

//if($wpuid == 0){
if(!is_user_logged_in()){
	$url = get_site_url();
	header( "Location: $url" ) ;
}

get_header(); ?>

<?php get_template_part('partials/search'); ?>
    <section class="results">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
					<div id="searchData">
                    <table class="table tk-proxima-nova">
                        <thead>
                            <tr>
                                <th>Player Name</th>
                                <th>Position</th>
                                <th>Graduation Year</th>
                                <th>State</th>
                                <th class="hidden-xs">Avg. Exit Velocity</th>
                                <th class="hidden-xs">Max Exit Velocity</th>
                                <th class="hidden-xs">Avg. Distance</th>
                                <th class="hidden-xs">Max Distance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="noresults">
                                <td colspan="8">No Results.</td>
                            </tr>
                        </tbody>
                    </table>

                    </div>
					<div id="loading" style='padding-left:530px; padding-top:150px; display:none;'><img class="gif" src="<?php echo get_template_directory_uri(); ?>/images/loading.gif" /></div>
                </div>
            </div>
        </div>
    </section>
<div class="modal fade" id="searchSave-modal" tabindex="-1" role="dialog">
        
        <div class="modal-dialog" role="document">
            <form data-async class="searchSave"  data-target="#searchSave-modal"  action="/some-endpoint" method="POST">
            <div class="modal-content">

            <div class="modal-body">
                <label for="">Save search as:</label>
                <input type="text" name="searchSaveName" id="searchSaveName" class="form-control" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">CANCEL</button>
                <input type="submit" class="btn btn-primary" value="SAVE"></button>
            </div>
            </div><!-- /.modal-content -->
            </form
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<?php get_footer(); ?>