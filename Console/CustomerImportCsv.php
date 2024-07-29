<?php
/**
 *  @Module         : Customer Import Csv
 *  @Package        : Vml_CustomerImport
 *  @Description    : Customer Import Csv
 */
namespace Vml\CustomerImport\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class CustomerImportCsv extends Command
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
       $this->setName('customer:importcsv sample.csv');
       $this->setDescription('Demo command line');
       
       parent::configure();
    }
    /**
    * method will run when the command is called via console
    * @param InputInterface $input
    * @param OutputInterface $output
    * @return int|void|null
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
     
        $mediaPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
        if (($open = fopen($mediaPath.'/customer_import_files/sample.csv', "r")) !== FALSE) 
        {
      
        while (($data = fgetcsv($open, 1000, ",")) !== FALSE) 
        {        
          $customerArray[] = $data; 
        }
        fclose($open);
        }
        foreach(array_slice($customerArray,1) as $key=>$value)
        {
        $customer = $this->customerFactory->create();
        $customer->setFirstname($value[0]);
        $customer->setLastname($value[1]);
        $customer->setEmail($value[2]);
        $customer->save();
        }
       $output->writeln("Customer CSV Import Successfully");
    }
}
