<?php get_header(); ?>

<?php get_template_part('partials/search'); ?>
    <section class="results">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
					<div id="searchData">
                    <table class="table tk-proxima-nova"><thead></tr><th>Player Name</th><th>Position</th><th>Bats</th><th>Throws</th><th>Height</th><th>Weight</th><th>Age</th><th>Graduation Year</th></tr></thead><tbody>
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




<?php get_footer(); ?>