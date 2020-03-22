<?php

namespace Drupal\user_registration_form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Http\ClientFactory;
use Drupal\Core\Link;
use Drupal\user_registration_form\Form\PostiApiSettingsForm;

/**
 * Defines a posti api service.
 */
class PostiApiService implements PostiApiServiceInterface {

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * MunicipalityProvider constructor.
   *
   * @param \Drupal\Core\Http\ClientFactory $clientFactory
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(
    ClientFactory $clientFactory,
    ConfigFactoryInterface $configFactory
  ) {
    $this->client = $clientFactory->fromOptions();
    $this->config = $configFactory->getEditable(PostiApiSettingsForm::CONFIG);
  }

  /**
   * {@inheritdoc}
   */
  public function get() {

    $config = $this->config->get('posti');

    if (empty($config['service_url'])) {
      \Drupal::messenger()->addError(t('Please enter correct URL in @settings_page.', [
        '@settings_page' => Link::createFromRoute(t('Municipality service'), 'user_registration_form.settings')->toString()
      ]));
    }

    $query = [
      'key' => $config['api_key'] ?? '',
      'limit' => $config['limit'] ?? '',
      'group' => 'municipality',
      'municipality' => '',
    ];

    $data = [];

    do {
      $response = $this->client->get($config['service_url'], [
        'query' => $query,
      ]);
      $body = Json::decode($response->getBody());

      if (empty($body['status']) || $body['status'] != 'success') {
        \Drupal::messenger()->addError(t('API request failure.'));
      }

      $municipalities = array_column($body['data'], 'municipality');
      $data = array_merge($data, $municipalities);

      if (empty($body['page'])) {
        break;
      }

      $current = $body['page']['current'] ?? 0;
      $total = $body['page']['total'] ?? 0;
      $query['page'] = ++$current;

    } while ($total && $current <= $total);


    return $data;
  }

}
