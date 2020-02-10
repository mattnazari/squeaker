<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'squeaker_user');
define('DB_PASS', 'password');
define('DB_NAME', 'squeaker');

function runQuery($sql) {
  // Connect to the database
  $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  mysqli_set_charset($connection, 'utf8mb4');
  if(mysqli_connect_errno()) {
    exit("Database connection failed: (" . mysqli_connect_errno() . ")");
  }

  // Run the query
  $results = mysqli_query($connection, $sql);
  if (!$results) {
    exit("Database query failed. ". mysqli_errno ($connection));
  }

  // Handle the results
  if (gettype($results) === 'boolean') {
    return $results; 
  }
  
  $count = mysqli_num_rows($results);
  $data = [];
  for ($i = 0; $i < $count; $i++) {
    $row = mysqli_fetch_assoc($results);
    $data[] = $row;
  }

  mysqli_free_result($results);
  mysqli_close($connection);

  return $data;
}


// returns a single associative array squeak
function getSqueak($squeakId) {
  $sql = "
  SELECT id, message, username, like_count as likeCount FROM squeaks
  WHERE id = $squeakId";

  $squeaks = runQuery($sql);
  return $squeaks[0];
}


// returns an associative array of all of the squeaks
function getAllSqueaks($username, $order) {
  $sql = "
  SELECT id, message, username, like_count as likeCount
  FROM squeaks
  WHERE deleted = false AND username LIKE '%$username%'
  IF('$order' = 1, ORDER BY created DESC)
  ";

  $squeaks = runQuery($sql);
  return $squeaks;
}

// saves a new squeak to the database
function saveNewSqueak($message, $username) {
  $sql = "
  INSERT INTO squeaks (message, username, like_count, created, deleted)
  VALUES ('$message', '$username', 0, now(), false)
  ";

  $squeaks = runQuery($sql);
  return $squeaks;
}

// deletes a single squeak
function deleteSqueak($squeakId) {
  $sql = "
  UPDATE squeaks
  SET deleted = true
  WHERE id = $squeakId
  ";

  $squeaks = runQuery($sql);
  return $squeaks;
}

// increments the like count of a single squeak
function incrementLikeCount($squeakId, $increment) {
  $sql = "
  UPDATE squeaks
  SET like_count = like_count + 1
  WHERE id = $squeakId
  ";

  $squeaks = runQuery($sql);
  return $squeaks;
}
