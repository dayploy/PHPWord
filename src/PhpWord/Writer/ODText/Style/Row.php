<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use XMLWriter;
use \PhpOffice\PhpWord\Style\Row as RowStyle;

/**
 * Table style writer.
 *
 */
class Row extends AbstractStyle
{
    /**
     * Write style.
     * https://docs.oasis-open.org/office/OpenDocument/v1.3/os/part3-schema/OpenDocument-v1.3-os-part3-schema.html#element-style_table-cell-properties
     */
    public function write(): void
    {
        /** @var RowStyle $style Type hint */
        $style = $this->getStyle();

        if (!$style instanceof RowStyle) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'table-row');
        $xmlWriter->startElement('style:table-row-properties');
        $this->writeFormattingObject($xmlWriter, $style);
        $xmlWriter->endElement(); // style:table-row
        $xmlWriter->endElement(); // style:style
    }

    private function writeFormattingObject(XMLWriter $xmlWriter, RowStyle $style)
    {
        // fo:background-color: Sets the background color of the cell.
        $this->setNotNullAttribute(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:background-color',
            value: $style->getBgColor(),
            prefix: '#',
        );
    }

    private function setNotNullAttribute(
        XMLWriter $xmlWriter,
        string $attributeName,
        mixed $value,
        string $suffix = '',
        string $prefix = '',
    ): void {
        if (null === $value) {
            return;
        }

        $formattedValue = sprintf('%s%s%s', $prefix, $value, $suffix);

        $xmlWriter->writeAttribute($attributeName, $formattedValue);
    }
}
