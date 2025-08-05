#!/bin/bash

# List of migration IDs to roll back
MIGRATION_IDS=(

#Part 1: Core and Basic Module Migrations
"upgrade_scorechart_abnormalpoints"
"upgrade_scorechart_abnormalpointstotal"
"upgrade_scorechart_beamcirc"
"upgrade_scorechart_circbase"
"upgrade_scorechart_circfirst"
"upgrade_scorechart_circsecond"
"upgrade_scorechart_circthird"
"upgrade_scorechart_crownpoints"
"upgrade_scorechart_crownpointstotal",
"upgrade_scorechart_d1",
"upgrade_scorechart_d2",
"upgrade_scorechart_f1",
"upgrade_scorechart_f2",
"upgrade_scorechart_f3",
"upgrade_scorechart_f4",
"upgrade_scorechart_f5",
"upgrade_scorechart_g1",
"upgrade_scorechart_g2",
"upgrade_scorechart_g3",
"upgrade_scorechart_g4",
"upgrade_scorechart_g5",
"upgrade_scorechart_g6",
"upgrade_scorechart_g7",
"upgrade_scorechart_g8",
"upgrade_scorechart_g9",
"upgrade_scorechart_g10",
"upgrade_scorechart_g11",
"upgrade_scorechart_g12",
"upgrade_scorechart_g13",
"upgrade_scorechart_g14",
"upgrade_scorechart_g15"
"upgrade_scorechart_finalscoreantler",
"upgrade_scorechart_finalscorehornandtusk",
)

# Loop through the migration IDs and roll them back
for migration_id in "${MIGRATION_IDS[@]}"
do
  lando drush migrate:import "$migration_id"
  
  # Check the exit status of the previous command
  if [ $? -eq 0 ]; then
    echo "Migration '$migration_id' imported successfully."
  else
    echo "Error: Migration '$migration_id' import failed."
  fi
done

echo "All selected scorechart migration imports have been processed."