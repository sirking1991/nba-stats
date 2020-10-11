<?php

require('interface.php');

class PlayersDataSource implements DataSource
{

    public function data($args)
    {
        $searchArgs = ['player', 'playerId', 'team', 'position', 'country'];
        $search = $args->filter(function ($value, $key) use ($searchArgs) {
            return in_array($key, $searchArgs);
        });

        $where = [];
        if ($search->has('playerId')) $where[] = "roster.id = '" . $search['playerId'] . "'";
        if ($search->has('player')) $where[] = "roster.name = '" . $search['player'] . "'";
        if ($search->has('team')) $where[] = "roster.team_code = '" . $search['team'] . "'";
        if ($search->has('position')) $where[] = "roster.position = '" . $search['position'] . "'";
        if ($search->has('country')) $where[] = "roster.nationality = '" . $search['country'] . "'";
        $where = implode(' AND ', $where);
        $sql = "
            SELECT roster.*
            FROM roster
            WHERE $where";
        $data = collect(query($sql))
            ->map(function ($item, $key) {
                unset($item['id']);
                return $item;
            });

        return $data;
    }
}
