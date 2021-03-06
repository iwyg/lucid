<?php

/*
 * This File is part of the Lucid\Module\Writer package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Module\Writer\Object;

use Lucid\Module\Writer\Writer;

/**
 * @class InterfaceWriter
 * @see AbstractWriter
 *
 * @package Lucid\Module\Writer
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
class InterfaceWriter extends AbstractWriter
{
    /**
     * constants
     *
     * @var array
     */
    protected $constants;

    /**
     * parent
     *
     * @var array
     */
    protected $parent;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $namespace
     * @param string $parent
     */
    public function __construct($name, $namespace = null, $parent = null)
    {
        parent::__construct($name, $namespace, $this->getTypeConstant());
        $this->setParent($parent);
        $this->constants = [];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTypeConstant()
    {
        return T_INTERFACE;
    }

    public function setConstants(array $constants)
    {
        $this->constants = [];

        foreach ($constants as $const) {
            $this->addConstant($const);
        }
    }

    /**
     * addConstant
     *
     * @param Constant $const
     *
     * @return void
     */
    public function addConstant(Constant $const)
    {
        $this->constants[] = $const;
    }

    /**
     * addMethod
     *
     * @param MethodInterface $method
     *
     * @return void
     */
    public function addMethod(MethodInterface $method)
    {
        if (!$method instanceof InterfaceMethod) {
            throw \InvalidArgumentException(
                sprintf('Method %s must be instance of "InterfaceMethod".', $method->getName())
            );
        }

        parent::addMethod($method);
    }

    /**
     * Set the parent Interface.
     *
     * @param string $parent
     *
     * @return void
     */
    public function setParent($parent)
    {
        if (null == $parent) {
            return;
        }

        if (null === $this->parent) {
            $this->getImportResolver()->add($parent);
            $this->parent = $parent;

            return;
        }

        throw new \BadMethodCallException('Cannot set parent Parent. already set.');
    }

    /**
     * prepareGenerate
     *
     * @return void
     */
    protected function prepareGenerate()
    {
        if (($parent = $this->getParent())) {
            if ($this->getImportResolver()->hasAlias($parent) || !$this->inNamespace($parent)) {
                $this->addUseStatement($this->getImportResolver()->getImport($parent));
            }
        }
    }

    /**
     * Get the parent Interface
     *
     * @return string|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * getObjectBody
     *
     * @param Writer $writer
     *
     * @return Writer
     */
    protected function writeObjectBody(Writer $writer)
    {
        foreach ($this->constants as $constant) {
            $writer->writeln($constant->generate());
        }

        if (!empty($this->methods)) {
            $writer->newline();
        }

        return parent::writeObjectBody($writer);
    }

    /**
     * hasItemsBeforeMethods
     *
     * @return boolean
     */
    protected function hasItemsBeforeMethods()
    {
        return !empty($this->constants);
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareObjDoc(DocBlock $doc)
    {
        if ($this->parent) {
            $resolver = $this->getImportResolver();
            $name = $resolver->hasAlias($this->parent) ? $resolver->getAlias($this->parent) : $this->getParent();
            $doc->unshiftAnnotation('see', $name);
        }

        $doc->unshiftAnnotation($this->getType(), $this->getName());
    }

    protected function getExtends()
    {
        if (null === ($parent = $this->getParent())) {
            return;
        }

        return sprintf(' extends %s', $this->getImportResolver()->getAlias($parent));
    }

    /**
     * getObjectDeclarationExtension
     *
     * @return string
     */
    protected function getObjectDeclarationExtension()
    {
        return $this->getExtends();
    }

    /**
     * generate
     *
     * @param mixed $raw
     *
     * @access public
     * @return string|Writer
     */
    public function generate($raw = self::RV_STRING)
    {
        $this->prepareGenerate();

        return parent::generate($raw);
    }
}
