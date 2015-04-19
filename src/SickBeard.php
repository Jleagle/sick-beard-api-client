<?php
namespace Jleagle\SickBeard;

use GuzzleHttp\Client as Guzzle;
use Jleagle\SickBeard\Enums\FutureSortEnum;
use Jleagle\SickBeard\Enums\FutureTypeEnum;
use Jleagle\SickBeard\Enums\LanguageEnum;
use Jleagle\SickBeard\Enums\LogEnum;
use Jleagle\SickBeard\Enums\SortEnum;

class SickBeard
{

  private $url;
  private $apiKey;

  private $debug = 0;
  private $profile = 0;
  private $help = 0;
  private $callback = '';

  public function __construct($url, $apiKey)
  {
    if (!$url || !$apiKey)
    {
      throw new \Exception('URL & API key are required');
    }

    $this->url    = $url;
    $this->apiKey = $apiKey;
  }

  /**
   * @param int  $tvdbId
   * @param int  $season
   * @param int  $episode
   * @param bool $fullPath
   *
   * @return \stdClass
   * @throws \Exception
   */
  public function episode($tvdbId, $season, $episode, $fullPath = false)
  {
    return $this->_request(
      [
        'cmd'       => 'episode',
        'tvdbid'    => $tvdbId,
        'season'    => $season,
        'episode'   => $episode,
        'full_path' => $fullPath,
      ]
    );
  }

  /**
   * @param int $tvdbId
   * @param int $season
   * @param int $episode
   *
   * @return \stdClass
   * @throws \Exception
   */
  public function episode_search($tvdbId, $season, $episode)
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
   * @param string $status - Use StatusEnum enum
   * @param int    $episode
   * @param bool   $force
   *
   * @throws \Exception
   * @return \stdClass
   */
  public function episode_setstatus($tvdbId, $season, $status, $episode = null, $force = false)
  {
    return $this->_request(
      [
        'cmd'     => 'episode.setstatus',
        'tvdbid'  => $tvdbId,
        'season'  => $season,
        'status'  => $status,
        'episode' => $episode,
        'force'   => $force,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return \stdClass
   * @throws \Exception
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
   * @param string $sort - Use FutureSortEnum enum
   * @param array  $type - An array of FutureTypeEnum values
   * @param bool   $paused
   *
   * @throws \Exception
   * @return \stdClass
   */
  public function future($sort = FutureSortEnum::DATE, $type = [FutureTypeEnum::LATER, FutureTypeEnum::MISSED, FutureTypeEnum::SOON, FutureTypeEnum::TODAY], $paused = null)
  {
    $type = implode('|', $type);

    return $this->_request(
      [
        'cmd'   => 'future',
        'sort'  => $sort,
        'type'  => $type,
        'pased' => $paused,
      ]
    );
  }

  /**
   * @param int    $limit
   * @param string $type - Use HistoryTypeEnum enum
   *
   * @return \stdClass
   * @throws \Exception
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
   * @return \stdClass
   * @throws \Exception
   */
  public function history_clear()
  {
    return $this->_request(
      [
        'cmd' => 'history.clear',
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function history_trim()
  {
    return $this->_request(
      [
        'cmd' => 'history.trim',
      ]
    );
  }

  /**
   * @param string $minLevel - Use LogEnum enum
   *
   * @return \stdClass
   * @throws \Exception
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
   * @return \stdClass
   * @throws \Exception
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
   * @param int     $tvdbid
   * @param string  $location
   * @param bool    $flattenFolders
   * @param string  $initial - todo - add enum
   * @param string  $archive - todo - add enum
   *
   * @return \stdClass
   * @throws \Exception
   */
  public function show_addexisting($tvdbid, $location, $flattenFolders = null, $initial = null, $archive = null)
  {
    return $this->_request(
      [
        'cmd'             => 'show.addexisting',
        'tvdbid'          => $tvdbid,
        'location'        => $location,
        'flatten_folders' => $flattenFolders,
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
   * @param string $status  - Use ShowStatusEnum enum
   * @param string $initial - todo - add enum
   * @param string $archive - todo - add enum
   *
   * @return \stdClass
   * @throws \Exception
   */
  public function show_addnew($tvdbId, $location = null, $lang = LanguageEnum::ENGLISH, $flattenFolders = null, $status = null, $initial = null, $archive = null)
  {
    return $this->_request(
      [
        'cmd'             => 'show.addnew',
        'tvdbid'          => $tvdbId,
        'location'        => $location,
        'lang'            => $lang,
        'flatten_folders' => $flattenFolders,
        'status'          => $status,
        'initial'         => $initial,
        'archive'         => $archive,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return \stdClass
   * @throws \Exception
   */
  public function show_cache($tvdbId)
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
   * @return \stdClass
   * @throws \Exception
   */
  public function show_delete($tvdbId)
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
   * @throws \Exception
   */
  public function show_getbanner($tvdbId)
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
   * @throws \Exception
   */
  public function show_getposter($tvdbId)
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
   * @return \stdClass
   * @throws \Exception
   */
  public function show_getquality($tvdbId)
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
   * @return \stdClass
   * @throws \Exception
   */
  public function show_pause($tvdbId, $pause = false)
  {
    return $this->_request(
      [
        'cmd'    => 'show.pause',
        'tvdbid' => $tvdbId,
        'pause'  => $pause,
      ]
    );
  }

  /**
   * @param int $tvdbId
   *
   * @return \stdClass
   * @throws \Exception
   */
  public function show_refresh($tvdbId)
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
   * @return \stdClass
   * @throws \Exception
   */
  public function show_seasonlist($tvdbId, $sort = SortEnum::DESCENDING)
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
   * @return \stdClass
   * @throws \Exception
   */
  public function show_seasons($tvdbId, $season = null)
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
   * @param int    $tvdbId
   * @param string $initial - todo - add enum
   * @param string $archive - todo - add enum
   *
   * @return \stdClass
   * @throws \Exception
   */
  public function show_setquality($tvdbId, $initial = null, $archive = null)
  {
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
   * @return \stdClass
   * @throws \Exception
   */
  public function show_stats($tvdbId)
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
   * @return \stdClass
   * @throws \Exception
   */
  public function show_update($tvdbId)
  {
    return $this->_request(
      [
        'cmd'    => 'show.update',
        'tvdbid' => $tvdbId,
      ]
    );
  }

  /**
   * @param string $sort - todo - add enum
   * @param bool   $paused
   *
   * @return \stdClass
   * @throws \Exception
   */
  public function shows($sort = 'id', $paused = null)
  {
    return $this->_request(
      [
        'cmd'    => 'shows',
        'sort'   => $sort,
        'paused' => $paused
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function shows_stats()
  {
    return $this->_request(
      [
        'cmd' => 'shows.stats',
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function sb()
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
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_addrootdir($location, $default = false)
  {
    return $this->_request(
      [
        'cmd'      => 'sb.addrootdir',
        'location' => $location,
        'default'  => $default,
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_checkscheduler()
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
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_deleterootdir($location)
  {
    return $this->_request(
      [
        'cmd'      => 'sb.deleterootdir',
        'location' => $location
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_forcesearch()
  {
    return $this->_request(
      [
        'cmd' => 'sb.forcesearch',
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_getdefaults()
  {
    return $this->_request(
      [
        'cmd' => 'sb.getdefaults',
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_getmessages()
  {
    return $this->_request(
      [
        'cmd' => 'sb.getmessages',
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_getrootdirs()
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
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_pausebacklog($pause = false)
  {
    return $this->_request(
      [
        'cmd'   => 'sb.pausebacklog',
        'pause' => $pause
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_ping()
  {
    return $this->_request(
      [
        'cmd' => 'sb.ping',
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_restart()
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
   * @param string $lang - Use LanguageEnum enum
   *
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_searchtvdb($name = null, $tvdbId = null, $lang = LanguageEnum::ENGLISH)
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
   * @param string $status - Use ShowStatusEnum enum
   * @param bool   $flattenFolders
   * @param string $initial
   * @param string $archive
   *
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_setdefaults($futureShowPaused = null, $status = null, $flattenFolders = null, $initial = null, $archive = null)
  {
    return $this->_request(
      [
        'cmd'                => 'sb.setdefaults',
        'future_show_paused' => $futureShowPaused,
        'status'             => $status,
        'flatten_folders'    => $flattenFolders,
        'initial'            => $initial,
        'archive'            => $archive,
      ]
    );
  }

  /**
   * @return \stdClass
   * @throws \Exception
   */
  public function sb_shutdown()
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
   * @return \stdClass
   * @throws \Exception
   */
  private function _request($params)
  {

    if ($this->debug)
    {
      $params['debug'] = 1;
    }
    if ($this->profile)
    {
      $params['profile'] = 1;
    }
    if ($this->help)
    {
      $params['help'] = 1;
    }
    if ($this->callback)
    {
      $params['callback'] = $this->callback;
    }

    $url   = $this->url . '/api/' . $this->apiKey . '/?';
    $query = http_build_query($params);

    $client = new Guzzle();
    $response = $client->get($url . $query);

    if($response->getStatusCode() != 200)
    {
      throw new \Exception('Invalid response');
    }

    $body        = $response->getBody();
    $contentType = $response->getHeader('content-type');

    if(strpos($contentType, 'json') !== false)
    {
      $array = json_decode($body, true);

      if(isset($array->result) && $array->result != 'success')
      {
        throw new \Exception($array->message);
      }

      return $array->data;
    }
    else
    {
      header('Content-Type: '.$contentType);
      return $body;
    }
  }

  /**
   * @param int $debug
   *
   * @return $this
   */
  public function setDebug($debug)
  {
    $this->debug = $debug;
    return $this;
  }

  /**
   * @param int $help
   *
   * @return $this
   */
  public function setHelp($help)
  {
    $this->help = $help;
    return $this;
  }

  /**
   * @param int $profile
   *
   * @return $this
   */
  public function setProfile($profile)
  {
    $this->profile = $profile;
    return $this;
  }

  /**
   * @param string $callback
   *
   * @return $this
   */
  public function setCallback($callback)
  {
    $this->callback = $callback;
    return $this;
  }

}
