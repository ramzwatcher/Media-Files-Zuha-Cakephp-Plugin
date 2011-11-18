<?PHP
/**
 * @author <joel@razorit.com>
 * @property Media $Media
 */
class MediaController extends AppController {


	var $name = 'Media';
	#var $uid;
	#var $uses = array('');
	var $allowedActions = array('index', 'view', 'notification');


	/*
	*	show an admin index or the videos of the current user?
	*/
	public function index() {
		$allMedia = $this->Media->find('all', array(
				'conditions' => array('Media.filename !=' => '')
			));

		$this->set('media', $allMedia);
	}//index()


	public function add() {
		#debug($this->request->data);
		#debug($this->request->params);
		if($this->request->data) {
                        $this->request->data['User']['id'] = $this->Auth->user('id');
			#debug($this->request->data);break;
			if ($this->Media->save($this->request->data)) {
				$this->Session->setFlash('Media saved and being encoded.');
                                $this->redirect('/media/media/edit/'.$this->Media->id);
			} else {
				$this->Session->setFlash('Invalid Upload.');
			}
		}

	}//upload()

        
        public function edit($mediaID = null) {
            /** @todo Finish up the edit code.. put in thumbnails probably */
		if($mediaID) {
			$theMedia = $this->Media->findById($mediaID);
			$this->set('theMedia', $theMedia);
		}
        }//edit()



        public function view($mediaID = null) {

		if($mediaID) {
                    $theMedia = $this->Media->findById($mediaID);
                    
                    $this->pageTitle = $theMedia['Media']['title'];
                    $this->set('theMedia', $theMedia);
		}

	}//view()


	public function notification() {
		debug($this->request->data);
		if($this->request->data) {
			$this->request->data = json_decode($this->request->data, true);
#			$this->Media->notify($data);
			// zencoder is notifying us that a Job is complete
			if($this->request->data['output']['state'] == 'finished') {

				#echo "w00t!\n";

				// If you're encoding to multiple outputs and only care when all of the outputs are finished
				// you can check if the entire job is finished.
				if($this->request->data['job']['state'] == 'finished') {
					echo "Dubble w00t!\n";

					// find this zencoder_job_id
					$encoder_job = $this->Media->find('first', array('conditions' => array('Media.zen_job_id' => $this->request->data['job']['id'])));
					# TODO : allow for multiple output URL's....
					$encoder_job['Media']['filename'] = $this->request->data['output']['url'];
					$this->Media->save($encoder_job);
				}

			} elseif($this->request->data['output']['state'] == 'cancelled') {
				echo "Cancelled!\n";
			} else {
				echo "Fail!\n";
				debug($this->request->data);
				echo $this->request->data['output']['error_message']."\n";
				echo $this->request->data['output']['error_link'];
			}

		}//if($outputID)

		$this->render(false);

	}//notification()


}//class{}