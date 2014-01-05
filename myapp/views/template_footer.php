          </div></div>
        </main>
      </div> <!-- col -->
    </div> <!-- row -->
    <footer class="main-footer text-right">
      <div class="container">
	<a href="#about" data-toggle="modal" data-target="#modalAbout">About</a>
	<a href="#terms" data-toggle="modal" data-target="#modalTerms">Terms Of Service</a>
	<a href="#privacy" data-toggle="modal" data-target="#modalPrivacy">Privacy Policy</a>
	<i class="fa fa-android text-android fa-lg"></i>
      </div>
    </footer> <!-- text-right -->
    <script src="/js/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/main.js"></script>
    <?php if (isset($js)) { ?>
    <script src="/js/<?= $js ?>.js"></script>
    <?php } ?>
  </body>
</html>
