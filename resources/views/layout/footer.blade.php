<html>
<footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    ThemeKita
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              2024, made with <i class="fa fa-heart heart text-danger"></i> by
              <a href="http://www.themekita.com">JOKOWO</a>
            </div>
            <div>
 
            </div>
          </div>
        </footer>
      </div>

    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset ('assets/js')}}/core/jquery-3.7.1.min.js"></script>
    <script src="{{ asset ('assets/js')}}/core/popper.min.js"></script>
    <script src="{{ asset ('assets/js')}}/core/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <!-- jQuery Scrollbar -->
    <script src="{{ asset ('assets/js')}}/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="{{ asset ('assets/js')}}/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset ('assets/js')}}/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="{{ asset ('assets/js')}}/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="{{ asset ('assets/js')}}/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset ('assets/js')}}/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset ('assets/js')}}/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="{{ asset ('assets/js')}}/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset ('assets/js')}}/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset ('assets/js')}}/kaiadmin.min.js"></script>

    @stack('scripts')
        <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Render semua @push('scripts') dari child views -->

     
    </script>
  </body>
</html>