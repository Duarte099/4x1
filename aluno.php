<?php 
  session_start();

  $estouEm = 2;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>4x1 | Alunos</title>
    <?php  
      include('./head.php'); 
    ?>
  </head>
  <body>
    <div class="wrapper">
      <?php  
        include('./sideBar.php'); 
      ?>
        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Alunos</h3>
              </div>
              <div class="ms-md-auto py-2 py-md-0">
                <a href="alunoCriar.php" class="btn btn-primary btn-round">Adicionar aluno</a>
              </div>
            </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table
                      id="add-row"
                      class="display table table-striped table-hover"
                    >
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Position</th>
                          <th>Office</th>
                          <th style="width: 10%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Tiger Nixon</td>
                          <td>System Architect</td>
                          <td>Edinburgh</td>
                          <td>
                            <div class="form-button-action">
                              <button
                                type="button"
                                data-bs-toggle="tooltip"
                                title=""
                                class="btn btn-link btn-primary btn-lg"
                                data-original-title="Edit Task"
                              >
                                <i class="fa fa-edit"></i>
                              </button>
                              <button
                                type="button"
                                data-bs-toggle="tooltip"
                                title=""
                                class="btn btn-link btn-danger"
                                data-original-title="Remove"
                              >
                                <i class="fa fa-times"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php  
      include('./endPage.php'); 
    ?>
  </body>
</html>
