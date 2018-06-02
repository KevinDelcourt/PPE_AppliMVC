<?php
/**
 * Classe de test des fonctions de fct.inc.php
 *
 * Ne couvre que les fonctions réalisées pour ce PPE
 * 
 *
 * @category  PPE
 * @package   GSB
 * @author    Kévin DELCOURT
 */

require '../includes/fct.inc.php';

class FctTest extends PHPUnit_Framework_TestCase {

    /**
     * @var FctTest
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new FctTest;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    /**
     * test de la fonction getMoisAntérieur($mois)
     */
    public function testGetMoisAnterieur0() {
        $this->assertEquals(
                '201804', getMoisAnterieur('201805')
        );
    }
    
    /**
     * test de la fonction getMoisAntérieur($mois)
     */
    public function testGetMoisAnterieur1() {
        
        $this->assertEquals(
                '201712', getMoisAnterieur('201801')
        );
        
    }
    /**
     * test de la fonction getMoisAntérieur($mois)
     */
    public function testGetMoisAnterieur2() {
        
        $this->assertEquals(
                '201711', getMoisAnterieur('201712')
        );

    }
    /**
     * test de la fonction getMoisAntérieur($mois)
     */
    public function testGetMoisAnterieur3() {
        
        $this->assertEquals(
                '201608', getMoisAnterieur('201609')
        );
    }
}