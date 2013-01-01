<?php

class TestController extends Zend_Controller_Action
{
    public function init()
    {
    }
    
    public function indexAction()
    {
        // Initialise a MongoDb user collection.    
        $mongo = new Model_Groups();
        
        // Fetches all documents
        $documents = $mongo->find();
        
        // Initialise array iterator, defaults to cursor.
        $documents->toArray();
        
        //Zend_Debug::dump($documents);
        
        echo "Count is: " . $documents->count() . "<br />";
        
        $group = $documents->offsetGet(0);
        $group->setAssociativeGroup('FunStuffGroup');
        echo "Full Group Name: " . $group->getName() . "  Group: " . $group->getAssociativeGroup() . ".<br />";
        
        $group = $documents->seek(2);
        $group->setAssociativeGroup('Marketers');
        echo "Full Group Name: " . $group->getName() . "  Group: " . $group->getAssociativeGroup() . ".<br />";
        
        $group = $documents->rewind();
        $group->setAssociativeGroup('BenJerry');
        echo "Full Group Name: " . $group->getName() . "  Group: " . $group->getAssociativeGroup() . ".<br />";
        
        $group = $documents->next();
        $group->setAssociativeGroup('Retailers');
        echo "Full Group Name: " . $group->getName() . "  Group: " . $group->getAssociativeGroup() . ".<br />";
        
        $group = $documents->current();
        $group->setAssociativeGroup('Associates');
        echo "Full Group Name: " . $group->getName() . "  Group: " . $group->getAssociativeGroup() . ".<br />";
        
        
        //Zend_Debug::dump($documents->toArray());
    }
}