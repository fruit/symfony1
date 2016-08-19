<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class sfServiceStorageConfigurator
 *
 * @package    symfony
 * @subpackage service
 * @author     Ilya Sabelnikov <fruit.dev@gmail.com>
 * @version    SVN: $Id$
 */
class sfServiceStorageConfigurator
{
  /**
   * @var sfDatabaseManager
   */
  protected $databaseManager;

  /**
   * sfServiceStorageConfigurator constructor.
   *
   * @param sfDatabaseManager $databaseManager
   */
  public function __construct(sfDatabaseManager $databaseManager)
  {
    $this->databaseManager = $databaseManager;
  }

  /**
   * @param sfStorage $storage
   */
  public function configure(sfStorage $storage)
  {
    $databaseName = $storage->getOption('database_name');

    $storage->setOption('database', $this->databaseManager->getDatabase($databaseName));
  }
}
