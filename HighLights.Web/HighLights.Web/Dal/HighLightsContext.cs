using HighLights.Web.Entities;
using Microsoft.EntityFrameworkCore;

namespace HighLights.Web.Dal
{
    public class HighLightsContext: DbContext
    {
        public virtual DbSet<Category> Categories { get; set; }
        public virtual DbSet<Clip> Clips { get; set; }
        public virtual DbSet<Formation> Formations { get; set; }
        public virtual DbSet<Match> Matchs { get; set; }
        public virtual DbSet<Substitution> Substitutions { get; set; }
        public virtual DbSet<Tag> Tags { get; set; }
        public virtual DbSet<TagAssignment> TagAssignments { get; set; }
        public virtual DbSet<CrawlLink> CrawlLinks { get; set; }
        public virtual DbSet<ImageServer> ImageServers { get; set; }

        public HighLightsContext(DbContextOptions<HighLightsContext> options) : base(options)
        {

        }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<Category>()
                .HasIndex(c => c.Slug)
                .IsUnique();

            modelBuilder.Entity<Match>()
                .HasIndex(c => c.Slug)
                .IsUnique();

            modelBuilder.Entity<Tag>()
                .HasIndex(c => c.Slug)
                .IsUnique();

            modelBuilder.Entity<TagAssignment>()
                .HasKey(c => new { c.MatchId, c.TagId });
        }
    }
}
