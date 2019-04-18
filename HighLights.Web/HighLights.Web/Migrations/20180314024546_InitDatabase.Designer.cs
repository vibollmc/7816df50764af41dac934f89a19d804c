﻿// <auto-generated />
using HighLights.Web.Dal;
using HighLights.Web.Entities.Enum;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Infrastructure;
using Microsoft.EntityFrameworkCore.Metadata;
using Microsoft.EntityFrameworkCore.Migrations;
using Microsoft.EntityFrameworkCore.Storage;
using Microsoft.EntityFrameworkCore.Storage.Internal;
using System;

namespace HighLights.Web.Migrations
{
    [DbContext(typeof(HighLightsContext))]
    [Migration("20180314024546_InitDatabase")]
    partial class InitDatabase
    {
        protected override void BuildTargetModel(ModelBuilder modelBuilder)
        {
#pragma warning disable 612, 618
            modelBuilder
                .HasAnnotation("MySql:ValueGenerationStrategy", MySqlValueGenerationStrategy.IdentityColumn)
                .HasAnnotation("ProductVersion", "2.0.1-rtm-125");

            modelBuilder.Entity("HighLights.Web.Entities.Category", b =>
                {
                    b.Property<int?>("Id")
                        .ValueGeneratedOnAdd();

                    b.Property<DateTime?>("CreatedAt");

                    b.Property<DateTime?>("DeletedAt");

                    b.Property<bool>("IsMenu");

                    b.Property<string>("Name")
                        .IsRequired()
                        .HasMaxLength(200);

                    b.Property<string>("Slug")
                        .IsRequired()
                        .HasMaxLength(200);

                    b.Property<DateTime?>("UpdatedAt");

                    b.HasKey("Id");

                    b.ToTable("Categories");
                });

            modelBuilder.Entity("HighLights.Web.Entities.Clip", b =>
                {
                    b.Property<int?>("Id")
                        .ValueGeneratedOnAdd();

                    b.Property<int?>("ClipType");

                    b.Property<DateTime?>("CreatedAt");

                    b.Property<DateTime?>("DeletedAt");

                    b.Property<int?>("LinkType");

                    b.Property<int?>("MatchId");

                    b.Property<string>("Name")
                        .IsRequired()
                        .HasMaxLength(50);

                    b.Property<DateTime?>("UpdatedAt");

                    b.Property<string>("Url")
                        .IsRequired()
                        .HasMaxLength(1000);

                    b.HasKey("Id");

                    b.HasIndex("MatchId");

                    b.ToTable("Clips");
                });

            modelBuilder.Entity("HighLights.Web.Entities.CrawlLink", b =>
                {
                    b.Property<int?>("Id")
                        .ValueGeneratedOnAdd();

                    b.Property<string>("BaseLink");

                    b.Property<DateTime?>("CreatedAt");

                    b.Property<DateTime?>("DeletedAt");

                    b.Property<int?>("Finished");

                    b.Property<int?>("FromPage");

                    b.Property<bool>("IsCircle");

                    b.Property<bool>("IsFinished");

                    b.Property<int?>("ToPage");

                    b.Property<DateTime?>("UpdatedAt");

                    b.HasKey("Id");

                    b.ToTable("CrawlLinks");
                });

            modelBuilder.Entity("HighLights.Web.Entities.Formation", b =>
                {
                    b.Property<int?>("Id")
                        .ValueGeneratedOnAdd();

                    b.Property<DateTime?>("CreatedAt");

                    b.Property<DateTime?>("DeletedAt");

                    b.Property<int?>("MatchId");

                    b.Property<string>("Name");

                    b.Property<int?>("Number");

                    b.Property<DateTime?>("UpdatedAt");

                    b.HasKey("Id");

                    b.HasIndex("MatchId");

                    b.ToTable("Formations");
                });

            modelBuilder.Entity("HighLights.Web.Entities.ImageServer", b =>
                {
                    b.Property<int?>("Id")
                        .ValueGeneratedOnAdd();

                    b.Property<DateTime?>("CreatedAt");

                    b.Property<DateTime?>("DeletedAt");

                    b.Property<string>("Password")
                        .IsRequired()
                        .HasMaxLength(50);

                    b.Property<string>("Patch")
                        .IsRequired()
                        .HasMaxLength(50);

                    b.Property<int>("Port");

                    b.Property<string>("ServerFtp")
                        .IsRequired()
                        .HasMaxLength(50);

                    b.Property<string>("ServerName")
                        .IsRequired()
                        .HasMaxLength(50);

                    b.Property<string>("ServerUrl")
                        .IsRequired()
                        .HasMaxLength(50);

                    b.Property<DateTime?>("UpdatedAt");

                    b.Property<string>("UserName")
                        .IsRequired()
                        .HasMaxLength(50);

                    b.HasKey("Id");

                    b.ToTable("ImageServers");
                });

            modelBuilder.Entity("HighLights.Web.Entities.Match", b =>
                {
                    b.Property<int?>("Id")
                        .ValueGeneratedOnAdd();

                    b.Property<int?>("CategoryId");

                    b.Property<DateTime?>("CreatedAt");

                    b.Property<DateTime?>("DeletedAt");

                    b.Property<string>("ImageName");

                    b.Property<int?>("ImageServerId");

                    b.Property<DateTime?>("MatchDate")
                        .IsRequired();

                    b.Property<string>("Slug")
                        .IsRequired()
                        .HasMaxLength(500);

                    b.Property<string>("Title")
                        .IsRequired()
                        .HasMaxLength(500);

                    b.Property<DateTime?>("UpdatedAt");

                    b.HasKey("Id");

                    b.HasIndex("CategoryId");

                    b.HasIndex("ImageServerId");

                    b.ToTable("Matchs");
                });

            modelBuilder.Entity("HighLights.Web.Entities.Substitution", b =>
                {
                    b.Property<int?>("Id")
                        .ValueGeneratedOnAdd();

                    b.Property<DateTime?>("CreatedAt");

                    b.Property<DateTime?>("DeletedAt");

                    b.Property<int?>("FormationId");

                    b.Property<int?>("Minutes");

                    b.Property<string>("Name");

                    b.Property<int?>("Number");

                    b.Property<DateTime?>("UpdatedAt");

                    b.HasKey("Id");

                    b.HasIndex("FormationId")
                        .IsUnique();

                    b.ToTable("Substitutions");
                });

            modelBuilder.Entity("HighLights.Web.Entities.Tag", b =>
                {
                    b.Property<int?>("Id")
                        .ValueGeneratedOnAdd();

                    b.Property<DateTime?>("CreatedAt");

                    b.Property<DateTime?>("DeletedAt");

                    b.Property<string>("Name")
                        .IsRequired()
                        .HasMaxLength(50);

                    b.Property<string>("Slug")
                        .IsRequired()
                        .HasMaxLength(50);

                    b.Property<DateTime?>("UpdatedAt");

                    b.HasKey("Id");

                    b.ToTable("Tags");
                });

            modelBuilder.Entity("HighLights.Web.Entities.TagAssignment", b =>
                {
                    b.Property<int>("MatchId");

                    b.Property<int>("TagId");

                    b.HasKey("MatchId", "TagId");

                    b.HasIndex("TagId");

                    b.ToTable("TagAssignments");
                });

            modelBuilder.Entity("HighLights.Web.Entities.Clip", b =>
                {
                    b.HasOne("HighLights.Web.Entities.Match", "Match")
                        .WithMany("Clips")
                        .HasForeignKey("MatchId");
                });

            modelBuilder.Entity("HighLights.Web.Entities.Formation", b =>
                {
                    b.HasOne("HighLights.Web.Entities.Match")
                        .WithMany("Formations")
                        .HasForeignKey("MatchId");
                });

            modelBuilder.Entity("HighLights.Web.Entities.Match", b =>
                {
                    b.HasOne("HighLights.Web.Entities.Category", "Category")
                        .WithMany("Matches")
                        .HasForeignKey("CategoryId");

                    b.HasOne("HighLights.Web.Entities.ImageServer", "ImageServer")
                        .WithMany("Matches")
                        .HasForeignKey("ImageServerId");
                });

            modelBuilder.Entity("HighLights.Web.Entities.Substitution", b =>
                {
                    b.HasOne("HighLights.Web.Entities.Formation", "Formation")
                        .WithOne("Substitution")
                        .HasForeignKey("HighLights.Web.Entities.Substitution", "FormationId");
                });

            modelBuilder.Entity("HighLights.Web.Entities.TagAssignment", b =>
                {
                    b.HasOne("HighLights.Web.Entities.Match", "Match")
                        .WithMany("TagAssignments")
                        .HasForeignKey("MatchId")
                        .OnDelete(DeleteBehavior.Cascade);

                    b.HasOne("HighLights.Web.Entities.Tag", "Tag")
                        .WithMany("TagAssignments")
                        .HasForeignKey("TagId")
                        .OnDelete(DeleteBehavior.Cascade);
                });
#pragma warning restore 612, 618
        }
    }
}
