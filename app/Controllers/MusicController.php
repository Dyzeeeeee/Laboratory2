<?php

namespace App\Controllers;

use App\Controllers\BaseController;



class MusicController extends BaseController
{
    private $musicModel;
    private $playlistModel;
    private $playmusicModel;

    public function __construct()
    {
        $this->musicModel = new \App\Models\MusicModel();
        $this->playlistModel = new \App\Models\PlaylistModel();
        $this->playmusicModel = new \App\Models\PlayMusicModel();
    }
    public function MusicPlayer()
    {



        $data = [
            'songs' => $this->musicModel->findAll(),
            'playlists' => $this->playlistModel->findAll(),
        ];

        return view('index', $data);
    }





    public function add_song()
    {
        $songTitle = $this->request->getPost('songTitle');
        $songFile = $this->request->getFile('songFile');


        $newName = $songTitle . '.mp3';


        $songFile->move(ROOTPATH . 'public/music_list', $newName);



        $data = [
            'ms_name' => $songTitle,
            'ms_path' => 'music_list/' . $newName,
        ];


        $this->musicModel->insert($data);

        return redirect()->to('/musicplayer');
    }


    public function create_playlist_modal()
    {
        $data = [
            'songs' => $this->musicModel->findAll(),
        ];
        return view('modals/create_playlist_modal', $data);
    }

    public function create_playlist()
    {


        $playlistName = $this->request->getPost('playlistName');



        $data = [
            'pl_name' => $playlistName,
        ];

        $playlistId = $this->playlistModel->insert($data);


        $selectedSongs = $this->request->getPost('selectedSongs');

        if (is_array($selectedSongs)) {

            foreach ($selectedSongs as $songId) {
                $playMusicData = [
                    'id_ms' => $songId,
                    'id_pl' => $playlistId,
                ];
                $this->playmusicModel->insert($playMusicData);
            }
        }

        return redirect()->to('/musicplayer');
    }

    public function deleteSong($songId)
    {




        $song = $this->musicModel->find($songId);


        $this->musicModel->delete($songId);


        return redirect()->to('/musicplayer');
    }
    public function addToPlaylist()
    {
        $musicID = $this->request->getPost('musicID');
        $playlistId = $this->request->getPost('playlistName');


        $playlist = $this->playlistModel->find($playlistId);

        if ($playlist) {

            $data = [
                'id_ms' => $musicID,
                'id_pl' => $playlistId,
            ];

            $this->playmusicModel->insert($data);

            return redirect()->to('/musicplayer');
        } else {

            return redirect()->to('/musicplayer')->with('error', 'Playlist not found.');
        }
    }

    public function deletePlaylist($playlistId)
    {




        $playlist = $this->playlistModel->find($playlistId);

        if ($playlist) {

            $this->playlistModel->delete($playlistId);





            return redirect()->to('/musicplayer');
        }
    }

    public function search()
    {

        $searchQuery = $this->request->getGet('search');





        $songs = $this->musicModel->searchSongs($searchQuery);


        $data = [
            'songs' => $songs,
            'playlists' => $this->playlistModel->findAll(),
        ];


        return view('index', $data);
    }



    public function playlist($playlistName)
    {

        $playlist = $this->playlistModel->where('pl_name', $playlistName)->first();

        if ($playlist) {

            $songsInPlaylist = $this->playmusicModel
                ->select('tbl_music.*')
                ->join('tbl_music', 'tbl_playmusic.id_ms = tbl_music.ms_id')
                ->where('tbl_playmusic.id_pl', $playlist['pl_id'])
                ->get()
                ->getResultArray();


            $data = [
                'playlists' => $this->playlistModel->findAll(),
                'songs' => $songsInPlaylist,
                'playlistName' => $playlistName,
                'playlistId' => $playlist['pl_id'], 
            ];

            return view('playlist_view', $data);
        } else {

            return redirect()->to('/musicplayer')->with('error', 'Playlist not found.');
        }
    }

    public function removeFromPlaylist($musicID, $playlistID)
    {
        
        $playlist = $this->playlistModel->find($playlistID);
    
        if (!$playlist) {
            return redirect()->to('/musicplayer')->with('error', 'Playlist not found.');
        }
    
        
        $playmusic = $this->playmusicModel
            ->where('id_ms', $musicID)
            ->where('id_pl', $playlistID)
            ->first();
    
        if (!$playmusic) {
            return redirect()->to('/musicplayer')->with('error', 'Song not found in the playlist.');
        }
    
        
        $this->playmusicModel->delete($playmusic['id']);
    
        
        $playlistName = $playlist['pl_name'];
    
        
        return redirect()->to("/musicplayer/playlist/{$playlistName}")
            ->with('success', 'Song removed from the playlist.');
    }
    
    
    
}
