<?php
/**
 *  @Module         : Customer Import Json
 *  @Package        : Vml_CustomerImport
 *  @Description    : Customer Import Json
 */
namespace Vml\CustomerImport\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class CustomerImportJson extends Command
{
     /**
     * @var CustomerFactory
     */
     protected $customerFactory;
     protected $_filesystem;

     /**
     *  @param CustomerFactory $customerFactory
     * 
     */
     public function __construct(

        CustomerFactory $customerFactory,
        \Magento\Framework\Filesystem $filesystem

        ) {
            parent::__construct();
            $this->customerFactory = $customerFactory;
            $this->_filesystem = $filesystem;
        }
    protected function configure()
    {
       $this->setName('customer:importjson sample.json');
       $this->setDescription('Demo command line');
       
       parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
     
        $mediaPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
        // Read the JSON file 
        $json = file_get_contents($mediaPath.'/customer_import_files/sample.json');
          
        // Decode the JSON file
        $customerJsonData = json_decode($json,true);
          
        foreach($customerJsonData as $value)
        {
        $customer = $this->customerFactory->create();
        $customer->setFirstname($value['fname']);
        $customer->setLastname($value['lname']);
        $customer->setEmail($value['emailaddress']);
        $customer->save();
        }
       $output->writeln("Customer Json Import Successfully");
    }
}
