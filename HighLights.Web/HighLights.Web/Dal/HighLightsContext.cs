using HighLights.Web.Entities;
using Microsoft.EntityFrameworkCore;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace HighLights.Web.Dal
{
    public class HighLightsContext: DbContext
    {
        public DbSet<Category> Categories { get; set; }
        public DbSet<Clip> Clips { get; set; }
        public DbSet<Formation> Formations { get; set; }
        public DbSet<Match> Matchs { get; set; }
        public DbSet<Substitution> Substitutions { get; set; }
        public DbSet<Tag> Tags { get; set; }
        public DbSet<TagAssignment> TagAssignments { get; set; }

        public HighLightsContext(DbContextOptions<HighLightsContext> options) : base(options)
        {

        }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<TagAssignment>()
                .HasKey(c => new { c.MatchId, c.TagId });
        }
    }
}
