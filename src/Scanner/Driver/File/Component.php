<?php


namespace Scanner\Driver\File;

use Scanner\Driver\ContextSupport;
use Scanner\Driver\File\System\Support;

class Component
{
    protected ?Support $support = null;

    protected function setSupport(Support $support): void
    {
        $oldProp = $this->support;
        if ($this->support !== null) {
            $this->support->uninstall($this);
            ContextSupport::getPropertySupport($this)->
            firePropertyEvent($this, 'UNINSTALL_SUPPORT', $oldProp, $support);
        }
        $this->support = $support;
        $this->support->install($this);
        ContextSupport::getPropertySupport($this)->
        firePropertyEvent($this, 'INSTALL_SUPPORT', $oldProp, $support);
    }

}