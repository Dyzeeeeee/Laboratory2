<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Music Player</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="<?= base_url('js/script.js') ?>" async></script>
  <link rel="stylesheet" type="text/css" href="<?= base_url('css/style.css') ?>">
  <?= $this->include('modals/add_song'); ?>
  <?= $this->include('modals/playlist'); ?>
  <?= $this->include('modals/create_playlist'); ?>
  <?= $this->include('modals/select_from'); ?>


</head>

<body>
  <!-- Search song -->
<form action="/musicplayer/search" method="get">
  <input type="search" name="search" id="search" placeholder="Search for a song">
  <button type="submit" class="btn btn-primary">Search</button>
</form>
<!-- Search song -->


  <!-- Playlist button -->

  <h1 >Music Player</h1>

  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#playlistModal">
    My Playlist
  </button>

  <!-- Add Song button -->
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSongModal">
    Add Song
  </button>
  <!-- Add Song button -->

  <!-- Audio controls -->
  <audio id="audio" controls></audio>
  <!-- Audio controls -->
  <!-- Music list -->
  <ul id="playlist">
    <?php foreach ($songs as $song) : ?>
      <li data-src="<?= base_url($song['ms_path']) ?>">
        <div class="song-container">
          <a href="javascript:void(0);" class="song-title"><?= $song['ms_name'] ?></a>
          <button class="btn btn-primary add-to-playlist-btn" data-song-id="<?= $song['ms_id'] ?>" data-bs-toggle="modal" data-bs-target="#addToPlaylistModal">Add to Playlist</button>
          <form action="/musicplayer/delete-song/<?= $song['ms_id'] ?>" method="post">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this song?')">Delete</button>
          </form>
        </div>
      </li>
    <?php endforeach; ?>
</ul>

  <!-- Music list -->
  <script>
    $(document).ready(function() {
      $('.add-to-playlist-btn').click(function() {
        
        var songId = $(this).data('song-id');

    
        $('#musicID').val(songId);
      });
    });
  </script>






</body>

</html>