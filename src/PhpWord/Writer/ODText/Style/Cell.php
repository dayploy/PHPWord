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
use \PhpOffice\PhpWord\Style\Cell as CellStyle;

/**
 * Table style writer.
 *
 */
class Cell extends AbstractStyle
{
    /**
     * Write style.
     * https://docs.oasis-open.org/office/OpenDocument/v1.3/os/part3-schema/OpenDocument-v1.3-os-part3-schema.html#element-style_table-cell-properties
     */
    public function write(): void
    {
        /** @var CellStyle $style Type hint */
        $style = $this->getStyle();

        if (!$style instanceof \PhpOffice\PhpWord\Style\Cell) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'table-cell');
        $xmlWriter->startElement('style:table-cell-properties');
        $this->writeFormattingObject($xmlWriter, $style);
        $xmlWriter->endElement(); // style:table-cell
        $xmlWriter->endElement(); // style:style
    }

    private function writeFormattingObject(XMLWriter $xmlWriter, CellStyle $style)
    {
        // fo:background-color: Sets the background color of the cell.
        $this->setNotNullAttribute(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:background-color',
            value: $style->getBgColor(),
            prefix: '#',
        );

        // fo:border:xxx Defines the border of the cell (style, color, width).
        $this->writeBorderFormatting(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:border-top',
            size: $style->getBorderTopSize(),
            style: $style->getBorderTopStyle(),
            color: $style->getBorderTopColor(),
        );
        $this->writeBorderFormatting(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:border-left',
            size: $style->getBorderLeftSize(),
            style: $style->getBorderLeftStyle(),
            color: $style->getBorderLeftColor(),
        );
        $this->writeBorderFormatting(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:border-right',
            size: $style->getBorderRightSize(),
            style: $style->getBorderRightStyle(),
            color: $style->getBorderRightColor(),
        );
        $this->writeBorderFormatting(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:border-bottom',
            size: $style->getBorderBottomSize(),
            style: $style->getBorderBottomStyle(),
            color: $style->getBorderBottomColor(),
        );

        // fo:padding: Sets the inner spacing (padding) around the content of the cell.
        $this->setNotNullAttribute(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:padding-top',
            value: $style->getPaddingTop(),
            suffix: 'pt',
        );
        $this->setNotNullAttribute(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:padding-left',
            value: $style->getPaddingLeft(),
            suffix: 'pt',
        );
        $this->setNotNullAttribute(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:padding-right',
            value: $style->getPaddingRight(),
            suffix: 'pt',
        );
        $this->setNotNullAttribute(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:padding-bottom',
            value: $style->getPaddingBottom(),
            suffix: 'pt',
        );

        // fo:wrap-option: Controls the text wrapping behavior within the cell.
        $this->setNotNullAttribute(
            xmlWriter: $xmlWriter,
            attributeName: 'fo:wrap-option',
            value: $style->getNoWrap() ? 'no-wrap': null,
        );
    }

    private function writeBorderFormatting(
        XMLWriter $xmlWriter,
        string $attributeName,
        ?int $size,
        ?string $style,
        ?string $color,
    ): void {
        if (is_null($size) && is_null($color) && is_null($style)) {
            return;
        }

        $size = $size ?? 1;
        $style = $style ?? 'solid';
        $color = $color ?? '000000';

        $value = sprintf('%s %s #%s', $size, $style, $color);

        $xmlWriter->writeAttribute($attributeName, $value);
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
