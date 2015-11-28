<?php
namespace Jleagle\SickBeard;

use Jleagle\CurlWrapper\Curl;
use Jleagle\SickBeard\Enums\FutureSortEnum;
use Jleagle\SickBeard\Enums\LanguageEnum;
use Jleagle\SickBeard\Enums\LogEnum;
use Jleagle\SickBeard\Enums\ShowsSortEnum;
use Jleagle\SickBeard\Enums\SortOrderEnum;
use Jleagle\SickBeard\Exceptions\SickBeardException;
use Packaged\Helpers\Strings;

class SickBeard
{
  protected $_url;
  protected $_apiKey;

  protected $_debug = 0;
  protected $_profile = 0;
  protected $_help = 0;
  protected $_callback = '';

  public function __construct($url, $apiKey)
  {
    $this->_url = $url;
    $this->_apiKey = $apiKey;
  }

  /**
   * @param int  $tvdbId
   * @param int  $season
   * @param int  $episode
   * @param bool $fullPath
   *
   * @return array
   */
  public function episode($tvdbId, $season, $episode, $fullPath = false)
  {
    return $this->_request(
      [
        'cmd'       => 'episode',
        'tvdbid'    => $tvdbId,
        'season'    => $season,
        'episode'   => $episode,
        'full_path' => $fullPath ? 1 : 0,
      ]
    );
  }

  /**
   * @param int $tvdbId
   * @param int $season
   * @param int $episode
   *
   * @return array
   */
  public function episodeSearch($tvdbId, $season, $episode)
  {
    return $this->_request(
      [
        'cmd'     => 'episode.search',
        'tvdbid'  => $tvdbId,
        'season'  => $season,
        'episode' => $episode,
      ]
    );
  }

  /**
   * @param int    $tvdbId
   * @param int    $season
   * @param string $status - StatusEnum
   * @param int    $episode
   * @param bool   $force
   *
   * @return array
   */
  public function episodeSetStatus(
    $tvdbId, $season, $status, $episode = null, $force = false
  )
  {
    return $this->_request(
      [
        'cmd'     => 'episode.setstatus',
        'tvdbid'  => $tvdbId,
        'season'  => $season,
        'status'  => $status,
        'episode' => $episode,
        'force'   => $force ? 1 : 0,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return array
   */
  public function exceptions($tvdbId = null)
  {
    return $this->_request(
      [
        'cmd'    => 'exceptions',
        'tvdbid' => $tvdbId
      ]
    );
  }

  /**
   * @param string $sort - FutureSortEnum
   * @param array  $type - FutureTypeEnum[]
   * @param bool   $paused
   *
   * @return array
   */
  public function future(
    $sort = FutureSortEnum::DATE, array $type = null, $paused = null
  )
  {
    if($type)
    {
      $type = implode('|', $type);
    }

    return $this->_request(
      [
        'cmd'   => 'future',
        'sort'  => $sort,
        'type'  => $type,
        'pased' => $paused ? 1 : 0,
      ]
    );
  }

  /**
   * @param int    $limit
   * @param string $type - HistoryTypeEnum
   *
   * @return array
   */
  public function history($limit = 100, $type = null)
  {
    return $this->_request(
      [
        'cmd'   => 'history',
        'limit' => $limit,
        'type'  => $type
      ]
    );
  }

  /**
   * @return array
   */
  public function historyClear()
  {
    return $this->_request(
      [
        'cmd' => 'history.clear',
      ]
    );
  }

  /**
   * @return array
   */
  public function historyTrim()
  {
    return $this->_request(
      [
        'cmd' => 'history.trim',
      ]
    );
  }

  /**
   * @param string $minLevel - LogEnum
   *
   * @return array
   */
  public function logs($minLevel = LogEnum::ERROR)
  {
    return $this->_request(
      [
        'cmd'       => 'logs',
        'min_level' => $minLevel,
      ]
    );
  }

  /**
   * @param $tvdbId
   *
   * @return array
   */
  public function show($tvdbId)
  {
    return $this->_request(
      [
        'cmd'    => 'show',
        'tvdbid' => $tvdbId,
      ]
    );
  }

  /**
   * @param int    $tvdbid
   * @param string $location
   * @param bool   $flattenFolders
   * @param array  $initial - InitialEnum[]
   * @param array  $archive - ArchiveEnum[]
   *
   * @return array
   */
  public function showAddExisting(
    $tvdbid, $location, $flattenFolders = null, array $initial = null,
    array $archive = null
  )
  {
    if($initial)
    {
      $initial = implode('|', $initial);
    }

    if($archive)
    {
      $archive = implode('|', $archive);
    }

    return $this->_request(
      [
        'cmd'             => 'show.addexisting',
        'tvdbid'          => $tvdbid,
        'location'        => $location,
        'flatten_folders' => $flattenFolders ? 1 : 0,
        'initial'         => $initial,
        'archive'         => $archive,
      ]
    );
  }

  /**
   * @param int    $tvdbId
   * @param string $location
   * @param string $lang
   * @param bool   $flattenFolders
   * @param string $status  - ShowStatusEnum
   * @param array  $initial - InitialEnum[]
   * @param array  $archive - ArchiveEnum[]
   *
   * @return array
   */
  public function showAddNew(
    $tvdbId, $location = null, $lang = LanguageEnum::ENGLISH,
    $flattenFolders = null, $status = null, array $initial = null,
    array $archive = null
  )
  {
    if($initial)
    {
      $initial = implode('|', $initial);
    }

    if($archive)
    {
      $archive = implode('|', $archive);
    }

    return $this->_request(
      [
        'cmd'             => 'show.addnew',
        'tvdbid'          => $tvdbId,
        'location'        => $location,
        'lang'            => $lang,
        'flatten_folders' => $flattenFolders ? 1 : 0,
        'status'          => $status,
        'initial'         => $initial,
        'archive'         => $archive,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return array
   */
  public function showCache($tvdbId)
  {
    return $this->_request(
      [
        'cmd'    => 'show.cache',
        'tvdbid' => $tvdbId,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return array
   */
  public function showDelete($tvdbId)
  {
    return $this->_request(
      [
        'cmd'    => 'show.delete',
        'tvdbid' => $tvdbId,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return string
   */
  public function showGetBanner($tvdbId)
  {
    return $this->_request(
      [
        'cmd'    => 'show.getbanner',
        'tvdbid' => $tvdbId,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return string
   */
  public function showGetPoster($tvdbId)
  {
    return $this->_request(
      [
        'cmd'    => 'show.getposter',
        'tvdbid' => $tvdbId,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return array
   */
  public function showGetQuality($tvdbId)
  {
    return $this->_request(
      [
        'cmd'    => 'show.getquality',
        'tvdbid' => $tvdbId,
      ]
    );
  }

  /**
   * @param int  $tvdbId
   * @param bool $pause
   *
   * @return array
   */
  public function showPause($tvdbId, $pause = false)
  {
    return $this->_request(
      [
        'cmd'    => 'show.pause',
        'tvdbid' => $tvdbId,
        'pause'  => $pause ? 1 : 0,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return array
   */
  public function showRefresh($tvdbId)
  {
    return $this->_request(
      [
        'cmd'    => 'show.refresh',
        'tvdbid' => $tvdbId,
      ]
    );
  }

  /**
   * @param int    $tvdbId
   * @param string $sort - Use SortEnum enum
   *
   * @return array
   */
  public function showSeasonList($tvdbId, $sort = SortOrderEnum::DESCENDING)
  {
    return $this->_request(
      [
        'cmd'    => 'show.seasonlist',
        'tvdbid' => $tvdbId,
        'sort'   => $sort,
      ]
    );
  }

  /**
   * @param int $tvdbId
   * @param int $season
   *
   * @return array
   */
  public function showSeasons($tvdbId, $season = null)
  {
    return $this->_request(
      [
        'cmd'    => 'show.seasons',
        'tvdbid' => $tvdbId,
        'season' => $season,
      ]
    );
  }

  /**
   * @param int   $tvdbId
   * @param array $initial - InitialEnum[]
   * @param array $archive - ArchiveEnum[]
   *
   * @return array
   */
  public function showSetQuality(
    $tvdbId, array $initial = null, array $archive = null
  )
  {
    if($initial)
    {
      $initial = implode('|', $initial);
    }

    if($archive)
    {
      $archive = implode('|', $archive);
    }

    return $this->_request(
      [
        'cmd'     => 'show.setquality',
        'tvdbid'  => $tvdbId,
        'initial' => $initial,
        'archive' => $archive,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return array
   */
  public function showStats($tvdbId)
  {
    return $this->_request(
      [
        'cmd'    => 'show.stats',
        'tvdbid' => $tvdbId,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return array
   */
  public function showUpdate($tvdbId)
  {
    return $this->_request(
      [
        'cmd'    => 'show.update',
        'tvdbid' => $tvdbId,
      ]
    );
  }

  /**
   * @param string $sort - SortSortEnum
   * @param bool   $paused
   *
   * @return array
   */
  public function shows($sort = ShowsSortEnum::ID, $paused = null)
  {
    return $this->_request(
      [
        'cmd'    => 'shows',
        'sort'   => $sort,
        'paused' => $paused ? 1 : 0
      ]
    );
  }

  /**
   * @return array
   */
  public function showsStats()
  {
    return $this->_request(
      [
        'cmd' => 'shows.stats',
      ]
    );
  }

  /**
   * @return array
   */
  public function sickBeard()
  {
    return $this->_request(
      [
        'cmd' => 'sb',
      ]
    );
  }

  /**
   * @param string $location
   * @param bool   $default
   *
   * @return array
   */
  public function sickBeardAddRootDirectory($location, $default = false)
  {
    return $this->_request(
      [
        'cmd'      => 'sb.addrootdir',
        'location' => $location,
        'default'  => $default ? 1 : 0,
      ]
    );
  }

  /**
   * @return array
   */
  public function sickBeardCheckScheduler()
  {
    return $this->_request(
      [
        'cmd' => 'sb.checkscheduler',
      ]
    );
  }

  /**
   * @param string $location
   *
   * @return array
   */
  public function sickBeardDeleteRootDirectory($location)
  {
    return $this->_request(
      [
        'cmd'      => 'sb.deleterootdir',
        'location' => $location
      ]
    );
  }

  /**
   * @return array
   */
  public function sickBeardForceSearch()
  {
    return $this->_request(
      [
        'cmd' => 'sb.forcesearch',
      ]
    );
  }

  /**
   * @return array
   */
  public function sickBeardGetDefaults()
  {
    return $this->_request(
      [
        'cmd' => 'sb.getdefaults',
      ]
    );
  }

  /**
   * @return array
   */
  public function sickBeardGetMessages()
  {
    return $this->_request(
      [
        'cmd' => 'sb.getmessages',
      ]
    );
  }

  /**
   * @return array
   */
  public function sickBeardGetRootDirectories()
  {
    return $this->_request(
      [
        'cmd' => 'sb.getrootdirs',
      ]
    );
  }

  /**
   * @param bool $pause
   *
   * @return array
   */
  public function sickBeardPauseBacklog($pause = false)
  {
    return $this->_request(
      [
        'cmd'   => 'sb.pausebacklog',
        'pause' => $pause ? 1 : 0
      ]
    );
  }

  /**
   * @return array
   */
  public function sickBeardPing()
  {
    return $this->_request(
      [
        'cmd' => 'sb.ping',
      ]
    );
  }

  /**
   * @return array
   */
  public function sickBeardRestart()
  {
    return $this->_request(
      [
        'cmd' => 'sb.restart',
      ]
    );
  }

  /**
   * @param string $name
   * @param int    $tvdbId
   * @param string $lang - LanguageEnum
   *
   * @return array
   */
  public function sickBeardSearchTvDb(
    $name = null, $tvdbId = null, $lang = LanguageEnum::ENGLISH
  )
  {
    return $this->_request(
      [
        'cmd'    => 'sb.searchtvdb',
        'name'   => $name,
        'tvdbid' => $tvdbId,
        'lang'   => $lang,
      ]
    );
  }

  /**
   * @param bool   $futureShowPaused
   * @param string $status  - ShowStatusEnum
   * @param bool   $flattenFolders
   * @param array  $initial - InitialEnum[]
   * @param array  $archive - ArchiveEnum[]
   *
   * @return array
   */
  public function sickBeardSetDefaults(
    $futureShowPaused = null, $status = null, $flattenFolders = null,
    array $initial = null, array $archive = null
  )
  {
    if($initial)
    {
      $initial = implode('|', $initial);
    }

    if($archive)
    {
      $archive = implode('|', $archive);
    }

    return $this->_request(
      [
        'cmd'                => 'sb.setdefaults',
        'future_show_paused' => $futureShowPaused ? 1 : 0,
        'status'             => $status,
        'flatten_folders'    => $flattenFolders ? 1 : 0,
        'initial'            => $initial,
        'archive'            => $archive,
      ]
    );
  }

  /**
   * @return array
   */
  public function sickBeardShutdown()
  {
    return $this->_request(
      [
        'cmd' => 'sb.shutdown',
      ]
    );
  }

  /**
   * @param array $params
   *
   * @return array
   *
   * @throws SickBeardException
   */
  protected function _request($params)
  {

    if($this->_debug)
    {
      $params['debug'] = 1;
    }
    if($this->_profile)
    {
      $params['profile'] = 1;
    }
    if($this->_help)
    {
      $params['help'] = 1;
    }
    if($this->_callback)
    {
      $params['callback'] = $this->_callback;
    }

    $url = $this->_url . '/api/' . $this->_apiKey;

    $response = Curl::get($url, $params)->run();

    if($response->getHttpCode() != 200)
    {
      throw new SickBeardException('Invalid response');
    }

    $contentType = $response->getContentType();

    if(Strings::contains($contentType, 'json', false))
    {
      $array = $response->getJson();

      if(isset($array['result']) && $array['result'] != 'success')
      {
        throw new SickBeardException($array['message']);
      }

      return $array['data'];
    }
    else
    {
      header('Content-Type: ' . $contentType);
      return $response->getOutput();
    }
  }

  /**
   * @param bool $debug
   *
   * @return $this
   */
  public function setDebug($debug = true)
  {
    $this->_debug = $debug ? 1 : 0;
    return $this;
  }

  /**
   * @param bool $help
   *
   * @return $this
   */
  public function setHelp($help = true)
  {
    $this->_help = $help ? 1 : 0;
    return $this;
  }

  /**
   * @param bool $profile
   *
   * @return $this
   */
  public function setProfile($profile = true)
  {
    $this->_profile = $profile ? 1 : 0;
    return $this;
  }

  /**
   * @param string $callback
   *
   * @return $this
   */
  public function setCallback($callback)
  {
    $this->_callback = $callback;
    return $this;
  }
}
