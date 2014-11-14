<?php
namespace Janus\Tests\ServiceRegistry\Service;

use Janus\ServiceRegistry\Entity\Connection;
use PHPUnit_Framework_TestCase;
use Phake;

use Janus\ServiceRegistry\Bundle\CoreBundle\DependencyInjection\ConfigProxy;
use Janus\ServiceRegistry\Connection\ConnectionDto;
use Janus\ServiceRegistry\Service\ConnectionService;
use Janus\ServiceRegistry\Entity\Connection\Revision\Metadata;

class ConnectionServiceTest extends PHPUnit_Framework_TestCase
{
    public function testFlattensMetadataOnSave()
    {
        // Create Conection service
        $entityManagerMock = Phake::mock('Doctrine\ORM\EntityManager');
        $mockDbConnection = Phake::mock('Doctrine\DBAL\Connection');
        Phake::when($entityManagerMock)
            ->getConnection()
            ->thenReturn($mockDbConnection);
        $config = new ConfigProxy(array(
            "metadatafields" => array(
                'saml20_idp' => array(
                    'foo:bar:baz' => array(
                        'supported' => array(
                            1
                        )
                    )
                )
            )
        ));
        $loggerMock = Phake::mock('Monolog\Logger');
        $connectionService = new ConnectionService(
            $entityManagerMock,
            $config,
            $loggerMock
        );

        // Mock existing connection
        $connectionRepositoryMock = Phake::mock('Janus\ServiceRegistry\Entity\ConnectionRepository');
        $connection = new Connection('fooConnection', 'saml20-idp', 'initial revision');
        Phake::when($connectionRepositoryMock)
            ->find(1)
            ->thenReturn($connection);
        Phake::when($entityManagerMock)
            ->getRepository('Janus\ServiceRegistry\Entity\Connection')
            ->thenReturn($connectionRepositoryMock);



        // Mock metadata dto
        $nestedMetadata = array(
            'foo' => array(
                'bar' => array(
                    'baz' => 1
                )
            )
        );

        // Save
        $connectionDto = new ConnectionDto();
        $connectionDto->setId(1);
        $connectionDto->setName('fooConnection');
        $connectionDto->setType('saml20-idp');
        $connectionDto->setRevisionNote('initial revision');
        $connectionDto->setMetadata($nestedMetadata);
        $connectionService->save($connectionDto);

        // Verify flattened metadata is saved
        $metadataRecord = new Metadata(
            $connection->getLatestRevision(),
            'foo:bar:baz',
            1
        );
        Phake::verify($entityManagerMock)->persist($metadataRecord);
    }
}