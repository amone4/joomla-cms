<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Utilities
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

require_once JPATH_PLATFORM. '/joomla/utilities/simplexml.php';

/**
 * mapCallback is the function called by the map routine. It simply
 * records where it's been in the report variable for the testMap
 * method to check after the run.
 *
 * @param JSimpleXMLElement The object that is calling
 * @param array			An array of passed arguments
 *
 * @return void
 */
function mapCallback( $object, $arguments )
{
	JSimpleXMLElementTest::$report[] = $arguments['msg'] . $object->name();
}

/**
 * Test class for JSimpleXMLElement.
 * Generated by PHPUnit on 2009-11-05 at 13:01:47.
 *
 * @package	Joomla.UnitTest
 * @subpackage Utilities
 */
class JSimpleXMLElementTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JSimpleXMLElement
	 */
	protected $object;

	/**
	 * @var array
	 */
	protected $child;

	/**
	 * @var array
	 */
	static $report;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->object = new JSimpleXMLElement('Test');
		$this->report = array();
		$this->child = array();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
	}

	/**
	 * Builds a non-trivial XML document tree
	 *
	 * @return void
	 */
	protected function buildXMLTree()
	{
		$this->child[0] = null;
		$this->child[1] = $this->object->addChild('Child1');
		$this->child[2] = $this->child[1]->addChild('Child2');
		$this->child[2]->setData('height');
		$this->child[2]->addAttribute('height', 300);
		$this->child[3] = $this->child[2]->addChild('Child3');
		$this->child[4] = $this->object->addChild('Child4');
		$this->child[4]->setData('Fred');
	}
	/**
	 * Test cases for building XML elements
	 *
	 * @return array
	 */
	function casesBuild()
	{
		return array(
			'basic' => array(
				'basic',
				array(XML_OPTION_SKIP_WHITE => true),
				0,
				'basic',
				array(XML_OPTION_SKIP_WHITE => true),
				true,
				0,
			),
			'defaults' => array(
				'default',
				null,
				null,
				'default',
				array(),
				false,
				0,
			),
		);
	}
	/**
	 * Testing testBuildingXMLElements().
	 *
	 * @param string Name of XML element to build
	 * @param array  Array of attributes to add to element
	 * @param int	Level of added node
	 * @param string Expected name to retrieve from Element
	 * @param array  Expected array of attributes and values assigned to element
	 * @param bool   Expected value for the "Skip White" attribute
	 * @param int	Expected value for level of node
	 *
	 * @return void
	 * @dataProvider casesBuild
	 */
	public function testBuildingXMLElements( $name, $options, $level, $expName,
	$expAttr, $expSkip, $expLevel ) {
		if ( is_null($options) )
		{
			$this->object = new JSimpleXMLElement($name);
		}
		elseif ( is_null($level) )
		{
			$this->object = new JSimpleXMLElement($name, $options);
		}
		else
		{
			$this->object = new JSimpleXMLElement( $name, $options, $level );
		}

		$this->assertThat(
			$this->object->name(),
			$this->equalTo($expName)
		);
		$this->assertThat(
			$this->object->attributes(),
			$this->equalTo($expAttr)
		);
		$this->assertThat(
			$this->object->attributes(XML_OPTION_SKIP_WHITE),
			$this->equalTo($expSkip)
		);
		$this->assertThat(
			$this->object->level(),
			$this->equalTo($expLevel)
		);
	}

	/**
	 * Test cases for manipulating attributes
	 *
	 * @return array
	 */
	function casesAttributes()
	{
		return array(
			'basic' => array(
				'height',
				300,
			),
		);
	}

	/**
	 *	Testing Add/Remove Attribute
	 *
	 * @param string Name of attribute to add to node.
	 * @param mixed  Value to assign to added attribute.
	 *
	 * @return void
	 * @dataProvider casesAttributes
	 */
	function testAddAndRemoveAttributes( $attName, $attValue )
	{
		$this->assertThat(
			$this->object->attributes($attName),
			$this->equalTo(null)
		);

		$this->object->addAttribute($attName, $attValue);
		$this->assertThat(
			$this->object->attributes($attName),
			$this->equalTo($attValue)
		);

		$this->object->removeAttribute($attName);
		$this->assertThat(
			$this->object->attributes($attName),
			$this->equalTo(null)
		);
	}

	/**
	 * Test cases for manipulating data
	 *
	 * @return array
	 */
	function casesData()
	{
		return array(
			'basic' => array(
				'height',
			),
		);
	}

	/**
	 *	Testing Add/Remove Data
	 *
	 * @param mixed Data to add to node and check for.
	 *
	 * @return void
	 * @dataProvider casesData
	 */
	function testAddAndRemoveData( $data )
	{
		$this->assertThat(
			$this->object->data(),
			$this->equalTo(null)
		);

		$this->object->setData($data);
		$this->assertThat(
			$this->object->data(),
			$this->equalTo($data)
		);
	}

	/**
	 * Test cases for manipulating children
	 *
	 * @return array
	 */
	function casesChildren()
	{
		return array(
			'Just1' => array(
				'first',
				array(
					'Att1',
					300
				),
			),
		);
	}

	/**
	 *	Test Add/Remove Children
	 *
	 * @param string Name of child node to add and check
	 * @param array  Key=>Value array of attributes for child node.
	 *
	 * @return void
	 * @dataProvider casesChildren
	 */
	function testAddAndRemoveChildren( $name, $options )
	{
		$this->assertThat(
			$this->object->children(),
			$this->equalTo(array())
		);

		$child = $this->object->addChild($name, $options);
		$this->assertThat(
			$child,
			$this->isInstanceOf('JSimpleXMLElement')
		);
		$this->assertThat(
			$child->attributes(),
			$this->equalTo($options)
		);

		$this->object->removeChild($child);
		$this->assertThat(
			$this->object->children(),
			$this->equalTo(array())
		);
	}

	/**
	 * Test cases for getting element by path
	 *
	 * @return array
	 */
	function casesPath()
	{
		return array(
			'SuccessLC' => array(
				'child1/child2/child3',
				3,
			),
			'SuccessUC' => array(
				'Child1/Child2/Child3',
				3,
			),
			'Failure' => array(
				'Child1/Child2/Child4',
				0,
			),
		);
	}

	/**
	 * Testing getElementByPath().
	 *
	 * @param string			The path ('/' as separator) to the XML node name
	 * @param JSimpleXMLElement The index into the XMLTree ($child) of the sought node.
	 *
	 * @return void
	 * @dataProvider casesPath
	 */
	public function testGetElementByPath($path, $expected)
	{
		$this->buildXMLTree();

		$this->assertThat(
			$this->object->getElementByPath($path),
			$this->equalTo($this->child[$expected])
		);
	}

	/**
	 * Testing testMap().
	 *
	 * @return void
	 */
	public function testMap()
	{
		$this->buildXMLTree();

		$this->object->map('mapCallback', array( 'msg' => "Here " ));

		$this->assertThat(
			JSimpleXMLElementTest::$report,
			$this->equalTo(
				array(
					'Here test',
					'Here child1',
					'Here child2',
					'Here child3',
					'Here child4'
				)
			)
		);
	}

	/**
	 * Testing toString().
	 *
	 * @return void
	 */
	public function testToString()
	{
		$this->buildXMLTree();

		$this->assertThat(
			$this->object->toString(),
			$this->equalTo(
				"\n<test>\n\t<child1>\n\t\t<child2 height=\"300\">\n\t\t\t<child3 />\n\t\t</child2>\n".
				"\t</child1>\n\t<child4>Fred</child4>\n</test>"
			),
			"Test with whitespace turned on"
		);
		$this->assertThat(
			$this->object->toString(false),
			$this->equalTo(
				"<test><child1><child2 height=\"300\"><child3 /></child2></child1><child4>Fred</child4></test>"
			),
			"Test without whitespace turned on"
		);
	}
}

