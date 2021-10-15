<?php
$request = $_SERVER['REQUEST_URI'];
$root = '/web-api/api';
$uri = str_replace($root, '', $request);

if (strpos($uri, 'upload-slip') == true) {
  require __DIR__ . '/controllers/upload/index.php';
  $Upload = new Upload;
  echo $Upload->uploadSlip();
} else if (strpos($uri, 'post-type') == true) {
  require __DIR__ . '/controllers/post_types/index.php';
  $PostType = new PostType;
  echo $PostType->getCats();
} else {
  switch ($uri) {
    // authentication begins
    case '/register':
      require __DIR__ . '/controllers/auth/index.php';
      $Auth = new Auth;
      echo $Auth->Register();
      break;
    case '/login':
      require __DIR__ . '/controllers/auth/index.php';
      $Auth = new Auth;
      echo $Auth->Login();
      break;
    case '/app-auth':
      require __DIR__ . '/controllers/auth/index.php';
      $Auth = new Auth;
      echo $Auth->AppAuth();
      break;
    case '/verify':
      require __DIR__ . '/controllers/user/index.php';
      $User = new User;
      echo $User->Verify();
      break;
    case '/reset-password':
      require __DIR__ . '/controllers/user/index.php';
      $User = new User;
      echo $User->ResetPassword();
      break;
    // authentication ends
    // create begins
    case '/add-categories':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->addCats();
      break;
    case '/add-posts':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->addPosts();
      break;
    case '/add-enrollment':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->addEnrollment();
      break;
    // create ends
    // read begins
    // read users begins
    case '/get-account':
      require __DIR__ . '/controllers/user/index.php';
      $User = new User;
      echo $User->AccountDetails();
      break;
    case '/get-user':
      require __DIR__ . '/controllers/user/index.php';
      $User = new User;
      echo $User->GetUser();
      break;
    case '/get-users':
      require __DIR__ . '/controllers/user/index.php';
      $User = new User;
      echo $User->GetUsers();
      break;
    case '/get-users-by-role':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getUsersByRole();
      break;
    // read users ends
    case '/get-custom-fields':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getCustomFields();
      break;
    case '/get-posts':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getPosts();
      break;
    case '/get-post':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getPost();
      break;
    case '/get-videos':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getVideos();
      break;
    case '/get-assigned-classes':
      require __DIR__ . '/controllers/user/index.php';
      $User = new User;
      echo $User->getAssignedClasses();
      break;
    case '/get-available-classes':
      require __DIR__ . '/controllers/user/index.php';
      $User = new User;
      echo $User->getAvailableClasses();
      break;
    // read locations begins
    case '/get-states':
      require __DIR__ . '/controllers/locs/index.php';
      $Locs = new Locs;
      echo $Locs->GetStates();
      break;
    case '/get-districts':
      require __DIR__ . '/controllers/locs/index.php';
      $Locs = new Locs;
      echo $Locs->GetDistricts();
      break;
    case '/get-cities':
      require __DIR__ . '/controllers/locs/index.php';
      $Locs = new Locs;
      echo $Locs->GetCities();
      break;
    case '/get-grades':
      require __DIR__ . '/controllers/locs/index.php';
      $Locs = new Locs;
      echo $Locs->GetGrades();
      break;
    case '/get-localization':
      require __DIR__ . '/controllers/localize/index.php';
      $Local = new Local;
      echo $Local->lang();
      break;
    // read locations ends
    case '/get-category':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getCat();
      break;
    case '/get-class-groups':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getClassGroups();
      break;
    case '/get-payment-summary':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getPaymentSummary();
      break;
    case '/get-payments':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getPayments();
      break;
    case '/get-enrolled-classes':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getEnrolledClasses();
      break;
    case '/get-enrolled-classes-full':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getEnrolledClassesFull();
      break;
    case '/get-lesson':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->getLesson();
      break;
    case '/get-feature-downloads':
      require __DIR__ . '/controllers/apps/index.php';
      $Downloads = new Downloads;
      echo $Downloads->getFeaturingDownloads();
      break;
    case '/set-attempts':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->setAttempts();
      break;
    case '/init-attempts':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->initAttempts();
      break;
    // read ends
    // update begins
    // update users begins
    case '/update-user':
      require __DIR__ . '/controllers/user/index.php';
      $User = new User;
      echo $User->UpdateUser();
      break;
    // update categories begins
    case '/update-categories':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->UpdateCat();
      break;
    // update categories ends
    case '/update-post':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->updatePost();
      break;
    case '/approve-payments':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->updatePayments();
      break;
    // update ends
    // delete begins
    case '/delete-category-permanently':
      require __DIR__ . '/controllers/post_types/index.php';
      $PostType = new PostType;
      echo $PostType->deleteCategoryPermanently();
      break;
    // delete ends
    default:
      http_response_code(404);
      echo '404 not found';
      break;
  }  
}