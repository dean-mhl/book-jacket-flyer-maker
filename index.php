<?php

 /*

  TODO:

    * add all this to github?
    * see about making images optimized for various social media (done)
    * possibly add widget/embed code?
    * make page or modal window that shows the whole list of saved flyers, maybe with a column that shows the webpages
    * make a button that changes printout from a 5 x 4 grid to something else?
    * make it so when a flyer is updated, its html page gets updated, too! (fixed?)
    * when view/edit button is clicked and flyer is loaded, resize oversized titles with textfill or something (fixed?)
    * don't hard code webserver address
    * saved html includes covers below the red line; is this okay?
    * test to ensure mod_rewrite is enabled and, if it's not, fall back to querystrings in urls of saved flyers
    * create an install page that prompts the user to enter database credentials, catalog url, and cover art source?
    * make a version that doesn't save and save with all MVLC libraries?
  */

    //Connection to MySQL
    require "config.php";

    //Connection to MySQL
    $con = mysqli_connect($host, $user, $password, $dbname);
    if(!$con) {
        die('Not Connected To Server');
    }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Book cover flyer maker</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.fontpicker.min.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script defer src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script defer src="js/jquery.textfill.min.js"></script>
    <script defer src="js/jquery.fontpicker.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script defer src="https://unpkg.com/sortablejs-make@1.0.1/Sortable.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <div id="container">
      <div id="left-panel" class="noprint">
        <a href="#" id="help-button">Help</a><h1>Make a book jacket flyer</h1>
        <form class="noprint" id="my-form" method="post">

            <h3>Step 1: add some covers</h3>
            <input type="hidden" id="flyer-id">
            <p>To add covers, enter some ISBNs or load <a target="_blank" title="open the catalog" href="https://mvlc.ent.sirsi.net/client/en_US/mvlc">a
            catalog search</a>. You can drag and drop covers to rearrange them. Click a cover to remove it.</p>
            <div id="step-1">
              <div id="isbns-container">
                <textarea name="isbns" id="isbns" placeholder="paste or scan ISBNs here, then click Add"></textarea><br>
                <button type="button" id="add-button">Add</button>
              </div>
              <div class="instructions">
                <div id="isbn-loading-options">
                  <div>
                    <b>New Books</b> (latest fiction & nonfiction)
                    <span class="load-button">
                      <button id="new-titles" type="button">Load</button>
                      <span class="loader" style="visibility: hidden;"><img src="images/ajax-loader-16.gif" alt="loading"></span>
                      <a href="#" class="load-reset" id="new-titles-reset" style="visibility: hidden;" title="click to reset">&times;</a>
                    </span>
                  </div>
                  <div>
                    <b>New Teen Room titles</b> (books, anime, etc.)
                    <span class="load-button">
                      <button id="teen-room-titles" type="button">Load</button>
                      <span class="loader" style="visibility: hidden;"><img src="images/ajax-loader-16.gif" alt="loading"></span>
                      <a href="#" class="load-reset" id="teen-room-titles-reset" style="visibility: hidden;" title="click to reset">&times;</a>
                    </span>
                  </div>
                  <div>
                    <input type="text" id="custom-url" name="custom-url" placeholder="paste the url of a catalog search results page">
                    <span class="load-button">
                      <button id="custom-catalog-url" disabled type="button">Load</button>
                      <span class="loader" style="visibility: hidden;"><img src="images/ajax-loader-16.gif" alt="loading"></span>
                      <a href="#" class="load-reset" id="custom-catalog-url-reset" style="visibility: hidden;" title="click to reset">&times;</a>
                    </span>
                 </div>
               </div>
              </div>
              <button type="button" id="clear-button">Clear Covers</button>
            </div>

            <h3 id="step-2">Step 2: add a header (optional)</h3>
            <textarea rows="5" name="header-text" id="header-text"></textarea>
            <div id="font-picker-container"><input style="display: none;" id="font-picker" type="text"> <a id="clear-cookie" href="#">clear favorite fonts</a></div>

            <h3>Step 3: save, print and/or download image</h3>
            <div class="step-3-buttons">
              <input type="text" placeholder="enter a flyer name here" id="name" name="name">
              <button type="button" id="save-button" disabled><span class="fa fa-save"></span> Save</button>
              <span id="webpage-option">
                <input type="checkbox" id="make-webpage" name="make-webpage" value="yes"><label for="make-webpage"> create webpage</label>
              </span>
              <button type="button" id="print-button"><span class="fa fa-print"></span> Print</button>
              <button type="button" id="download-image-button"><span class="fa fa-download"></span> Download</button>
              <button type="button" id="cancel-button">Clear All</button>
            </div>
            <div>
              <button type="button" title="Just the first three rows of covers are used." id="download-image-button-instagram"><span class="fa fa-brands fa-facebook"></span> <span class="fa fa-brands fa-instagram"></span> Download for social media</button>
            </div>
            <p id="flyer-url-container">webpage: <a target="_blank" href="" id="flyer-url"></a></p>
        </form>
<?php
  $sql = "SELECT DISTINCT f.id, f.name, f.has_url, f.timestamp FROM flyers f INNER JOIN covers c ON f.id = c.flyer_id ORDER BY f.timestamp DESC";
  $flyers = mysqli_query($con, $sql);
?>

        <div class="saved-flyers">
          <hr>
          <h2>saved flyers</h2>
          <div class="table-window">
            <table>
              <thead>
                <tr>
                  <th class="flyer-name">Name</th>
                  <th class="last-modified">Last modified</th>
                  <th class="operations">Operations</th>
                </tr>
              </thead>
              <tbody>
<?php while($row = mysqli_fetch_assoc($flyers)){ ?>
                  <tr id="<?php echo $row['id']; ?>">
                    <td class="flyer-name"><?php echo $row['name']; ?></td>
                    <td class="last-modified"><?php echo date("m/d/y, h:i a", $row['timestamp'])?></td>
                    <td class="operations">
                      <button type="button" class="edit" data-id="<?php echo $row['id'] ?>">view/edit</button>
                      <button type="button" class="delete" data-id="<?php echo $row['id'] ?>">delete</button>
                    </td>
                  </tr>
<?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div> <!-- end left-panel -->

      <div id="book">
        <div id="header"><span></span></div>
        <ul id="sortable"></ul>
      </div>

    </div> <!-- end container -->

    <!-- dialog boxes -->
    <div id="dialog-confirm-operation" title="Confirm operation" style="display: none"></div>

    <div id="dialog-confirm-overwrite" title="Confirm overwrite" style="display: none">
      <p>A flyer exists with that name. Do you want to overwrite it?</p>
    </div>

    <div id="help" title="How to use" style="display: none;">
      <p>This page is designed to make flyers that display book covers.</p>
      <p>If you have a cart full of books you want to advertise, you can scan their ISBNs into the box in the upper-left corner, click the Add button, and their
       covers will appear on the screen. You can also click the Load button next to <b>New Books</b> or <b>New Teen Room titles</b> to get covers for books from those
       collections. Or you can copy and paste the url of a search results page. </p>

      <p>The layout is optimized for an 8&half; &times; 11" sheet and produces four rows of five covers each, or 20 covers total. The Load buttons fetches a maximum of 12 covers
    at a time, so you'll need to click Load at least twice to get enough covers to fill the flyer.</p>

      <p>If you have more than 20 flyers, only the first 20 will be printed. Likewise, if you choose to download an image of the flyer, only the first 20 covers will be shown. 
       However, if you save the flyer, all the covers will be saved and will appear on the webpage that gets created when you save the flyer.
      (The Download for social media button produces an image that is 1080px × 1080px and only uses the first 15 covers.)
      </p>

      <p>You can rearrange covers through drag-and-drop and delete unwanted ones by clicking on them.</p>

      <p>If you add a header, it will be automatically sized and centered. You can select from a variety of fonts, some of which are available in different weights and/or in italics.
    The font selector lists first those fonts you use the most or have designated a "favorite" (which is done by selecting a font and then then clicking its heart icon). 
    You can clear the saved Favorites by clicking the "clear favorite fonts" link.</p>

      <p>Saving a flyer lets you modify its covers and/or header afterward. It also creates a webpage for that flyer. The covers on the webpage may not be arranged in a 5 &times; 4 grid,
       but instead will wrap and flow in such a way as to look good on any screen size. The webpage's layout is <em>not</em> optimized for printing. The covers on the webpage are 
       clickable and will take the user to their respective entries in the catalog. The webpage's url does not change, even if you modify a flyer and 
       save it again. If you delete a saved flyer, you'll delete its webpage as well.</p>

      <p>Printing works best when using modern drivers for business-class printers from Xerox, HP or Konica Minolta. 
       If the printout looks substandard, you can try using your OS's print-to-PDF function to see if it looks any better.</p>
      <p>Clicking Download will download an image of the displayed flyer in PNG format. This can be used for social media or thumbnail creation. 
       The image file will likely not print well; printing the flyer directly is always preferred. </p>
    </div>

    <div id="flyer-saved" style="display: none; text-align: center;"></div>

    <div id="favorites-cleared" style="display: none; text-align: center;"></div>

    <div id="no-more-results" title="No more results" style="display: none;">
      <p>There are no more results to retrieve.</p>
    </div>

    <div id="no-results" title="No results" style="display: none;">
     <p>No results. Please try another search.</p>
    </div>

    <div id="no-isbns" title="No ISBNs" style="display: none;">
      <p>You must add some ISBNs before you can save.</p>
    </div>

  </body>
</html>
