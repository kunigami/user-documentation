<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the BSD-style license found in the
 *  LICENSE file in the root directory of this source tree. An additional grant
 *  of patent rights can be found in the PATENTS file in the same directory.
 *
 */

namespace Facebook\GFM\UnparsedBlocks;

use type Facebook\GFM\Blocks\BlockSequence as ASTNode;
use namespace Facebook\GFM\Inlines;
use namespace HH\Lib\Vec;

/**  Not used by the core engine; useful for extensions when a single piece
 * of syntax might want to create multiple blocks. */
final class BlockSequence extends Block {
  private vec<Block> $children;

  final public function __construct(
    vec<?Block> $children,
  ) {
    $this->children = Vec\filter_nulls($children);
  }

  final public static function flatten(?Block ...$children): this {
    return new self(vec($children));
  }

  public static function consume(
    Context $_,
    vec<string> $_,
  ): ?(Block, vec<string>) {
    invariant_violation('should never be called');
  }

  public function withParsedInlines(
    Inlines\Context $context,
  ): ASTNode {
    return new ASTNode(
      Vec\map($this->children, $child ==> $child->withParsedInlines($context)),
    );
  }
}
