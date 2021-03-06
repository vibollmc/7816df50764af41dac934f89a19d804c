﻿using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;

namespace Football.Show.Entities
{
    public class Tag : Base
    {
        [Required, MaxLength(50)]
        public string Name { get; set; }
        [Required, MaxLength(50)]
        public string Slug { get; set; }

        public virtual ICollection<TagAssignment> TagAssignments { get; set; }
    }
}
